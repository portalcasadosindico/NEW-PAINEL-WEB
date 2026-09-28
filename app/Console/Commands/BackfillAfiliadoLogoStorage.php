<?php

namespace App\Console\Commands;

use App\Models\Afiliado;
use App\Uteis\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackfillAfiliadoLogoStorage extends Command
{
    protected $signature = 'afiliado:backfill-logo-storage
        {--dry-run : Apenas simula, sem copiar arquivo nem gravar no banco}
        {--limit= : Limita a quantidade de afiliados processados}
        {--afiliado= : Processa só este afiliado.id específico}';

    protected $description = 'Copia logos de afiliado enviadas pelo Painel (storage do Laravel) pro storage compartilhado com a API .NET, e atualiza afiliado.logo pro novo caminho - sem isso essas logos nunca aparecem no app (bug real, ver caso MALIVOR, sessão 2026-09-28).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $afiliadoFiltro = $this->option('afiliado');

        $this->info($dryRun ? '[DRY RUN] Nenhuma copia de arquivo nem gravacao no banco.' : 'Copiando arquivos e gravando no banco.');

        $query = Afiliado::whereNotNull('logo')
            ->where('logo', '!=', '')
            ->where('logo', '!=', 'no-image-perfil.png')
            ->where('logo', 'not like', 'user-%');

        if ($afiliadoFiltro) {
            $query->where('id', $afiliadoFiltro);
        }

        if ($limit) {
            $query->limit($limit);
        }

        $afiliados = $query->get();

        $this->info("Afiliados a processar: {$afiliados->count()}");
        $bar = $this->output->createProgressBar($afiliados->count());
        $bar->start();

        $atualizados = 0;
        $arquivoNaoEncontrado = 0;
        $falhas = 0;

        foreach ($afiliados as $afiliado) {
            $bar->advance();

            $logoAntiga = $afiliado->logo;
            $srcFull = Storage::disk('public')->path($logoAntiga);

            if (!is_file($srcFull)) {
                $arquivoNaoEncontrado++;
                $this->line(" -> afiliado #{$afiliado->id}: arquivo nao encontrado em {$srcFull} (logo='{$logoAntiga}')");
                continue;
            }

            if (!$afiliado->usuario_app_id) {
                $falhas++;
                $this->line(" -> afiliado #{$afiliado->id}: sem usuario_app_id, pulando");
                continue;
            }

            try {
                $bytes = file_get_contents($srcFull);
                $ext = strtolower(pathinfo($logoAntiga, PATHINFO_EXTENSION) ?: 'jpg');

                if ($dryRun) {
                    $hash = strtolower(md5($bytes));
                    $pasta = $ext === 'pdf' ? 'documentos' : 'imagens';
                    $novoPath = "user-{$afiliado->usuario_app_id}/afiliado/{$pasta}/{$hash}.{$ext}";
                    $this->line(" -> afiliado #{$afiliado->id}: '{$logoAntiga}' -> '{$novoPath}' [dry-run]");
                } else {
                    $novoPath = Url::salvarBytesNoStorageCompartilhado($bytes, $afiliado->usuario_app_id, $ext);
                    $afiliado->logo = $novoPath;
                    $afiliado->save();
                    $this->line(" -> afiliado #{$afiliado->id}: '{$logoAntiga}' -> '{$novoPath}'");
                }

                $atualizados++;
            } catch (\Exception $e) {
                $falhas++;
                $this->line(" -> afiliado #{$afiliado->id}: erro - {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Atualizados: {$atualizados}");
        $this->info("Arquivo nao encontrado no disco: {$arquivoNaoEncontrado}");
        $this->info("Falhas: {$falhas}");

        return self::SUCCESS;
    }
}
