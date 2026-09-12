<?php

namespace App\Console\Commands;

use App\Models\FranqueadoRegiao;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\Util;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BackfillAutentiqueShortLinks extends Command
{
    protected $signature = 'autentique:backfill-short-links
        {--dry-run : Apenas simula, sem gravar no banco}
        {--limit= : Limita a quantidade de documentos processados}
        {--documento= : Processa só este documento_id_autentique específico}
        {--debug : Imprime o JSON bruto das assinaturas retornadas pelo Autentique}
        {--sleep=1 : Segundos de espera entre chamadas à API do Autentique}';

    protected $description = 'Preenche short_link de assinaturas pendentes (orcamento_assinatura) que já têm documento_id_autentique salvo mas nunca tiveram o link capturado (bug corrigido em DocumentosAutentique.php, 2026-08-28).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $sleepSeconds = (int) $this->option('sleep');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info($dryRun ? '[DRY RUN] Nenhuma gravação será feita.' : 'Gravando no banco.');

        $documentoFiltro = $this->option('documento');

        $query = OrcamentoAssinatura::whereNull('signed')
            ->where(function ($q) {
                $q->whereNull('short_link')->orWhere('short_link', '');
            })
            ->whereNotNull('documento_id_autentique')
            ->where('documento_id_autentique', '!=', '')
            ->whereNull('deleted_at');

        if ($documentoFiltro) {
            $query->where('documento_id_autentique', $documentoFiltro);
        }

        $documentoIds = $query->distinct()->pluck('documento_id_autentique');

        if ($limit) {
            $documentoIds = $documentoIds->take($limit);
        }

        $this->info("Documentos a processar: {$documentoIds->count()}");
        $bar = $this->output->createProgressBar($documentoIds->count());
        $bar->start();

        $atualizados = 0;
        $semToken = 0;
        $falhaApi = 0;
        $semMatch = 0;

        foreach ($documentoIds as $documentoId) {
            $bar->advance();

            $rows = OrcamentoAssinatura::where('documento_id_autentique', $documentoId)
                ->whereNull('signed')
                ->where(function ($q) {
                    $q->whereNull('short_link')->orWhere('short_link', '');
                })
                ->whereNull('deleted_at')
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $orcamento = Orcamento::where('documento_id_autentique', $documentoId)->first();
            if (!$orcamento) {
                $primeiraLinha = $rows->first();
                $orcamento = Orcamento::find($primeiraLinha->orcamento_id);
            }
            if (!$orcamento || !$orcamento->regiao_id) {
                $semToken++;
                continue;
            }

            $franqueadoRegiao = FranqueadoRegiao::where('regiao_id', $orcamento->regiao_id)
                ->where('status', 'ativo')
                ->orderBy('id', 'desc')
                ->first();
            if (!$franqueadoRegiao) {
                $semToken++;
                continue;
            }

            $token = Util::getTokenAutentique($franqueadoRegiao->franqueado_id);
            if (!$token) {
                $semToken++;
                continue;
            }

            try {
                $res = json_decode(DocumentosAutentique::listById($token, $documentoId));
            } catch (\Exception $e) {
                Log::error('backfill-short-links: exceção ao consultar Autentique', [
                    'documento_id_autentique' => $documentoId,
                    'erro' => $e->getMessage(),
                ]);
                $falhaApi++;
                if ($sleepSeconds > 0) sleep($sleepSeconds);
                continue;
            }

            if (!$res || !isset($res->data->document->signatures)) {
                $falhaApi++;
                if ($sleepSeconds > 0) sleep($sleepSeconds);
                continue;
            }

            $assinaturas = $res->data->document->signatures;

            if ($this->option('debug')) {
                $this->line(json_encode($assinaturas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                foreach ($rows as $row) {
                    $this->line("ROW: id={$row->id} tipo={$row->tipo_usuario} email={$row->email} nome_assinante=" . var_export($row->nome_assinante, true));
                }
            }

            $encontrouAlguma = false;

            foreach ($rows as $row) {
                $shortLink = null;
                $publicId = null;
                $signedAt = null;

                foreach ($assinaturas as $assinatura) {
                    $emailApi = isset($assinatura->user) && $assinatura->user
                        ? ($assinatura->user->email ?? null)
                        : ($assinatura->email ?? null);

                    $bateEmail = $emailApi && $row->email && strcasecmp(trim($emailApi), trim($row->email)) === 0;
                    $bateNome = $row->nome_assinante && isset($assinatura->name)
                        && trim($assinatura->name) === trim($row->nome_assinante);

                    if ($bateEmail || $bateNome) {
                        $publicId = $assinatura->public_id ?? null;
                        $shortLink = $assinatura->link->short_link ?? null;
                        $signedAt = isset($assinatura->signed->created_at)
                            ? date('Y-m-d H:i:s', strtotime($assinatura->signed->created_at))
                            : null;
                        break;
                    }
                }

                // O campo link.short_link do documento vem null depois que o Autentique já
                // enviou o e-mail de convite - precisa gerar um link fresco via a mutation
                // createLinkToSignature(public_id), que funciona a qualquer momento antes de
                // assinar.
                if (!$shortLink && $publicId) {
                    try {
                        $resLink = json_decode(DocumentosAutentique::createLinkToSignature($token, $publicId));
                        $shortLink = $resLink->data->createLinkToSignature->short_link ?? null;
                    } catch (\Exception $e) {
                        Log::error('backfill-short-links: falha ao gerar link fresco', [
                            'public_id' => $publicId,
                            'erro' => $e->getMessage(),
                        ]);
                    }
                    if ($sleepSeconds > 0) sleep($sleepSeconds);
                }

                if ($shortLink || $publicId) {
                    $encontrouAlguma = true;
                    if (!$dryRun) {
                        $row->public_id = $publicId ?: $row->public_id;
                        if ($shortLink) $row->short_link = $shortLink;
                        if ($signedAt) $row->signed = $signedAt;
                        $row->save();
                    }
                    if ($shortLink) $atualizados++;
                    $this->line(" -> orcamento #{$row->orcamento_id} ({$row->tipo_usuario}, {$row->email}): " . ($shortLink ? "link capturado" : "public_id salvo, sem link") . ($dryRun ? ' [dry-run]' : ''));
                }
            }

            if (!$encontrouAlguma) {
                $semMatch++;
            }

            if ($sleepSeconds > 0) sleep($sleepSeconds);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Atualizados: {$atualizados}");
        $this->info("Sem token/franqueado resolvido: {$semToken}");
        $this->info("Falha na API do Autentique: {$falhaApi}");
        $this->info("Documento OK mas nenhuma assinatura bateu (email/nome): {$semMatch}");

        return self::SUCCESS;
    }
}
