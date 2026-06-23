<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DecryptSindicoFields extends Command
{
    protected $signature = 'sindico:decrypt-fields {--dry-run : Apenas simula, sem gravar no banco}';

    protected $description = 'Decripta nome, CPF, telefone e numero_documento da tabela sindico e regrava em texto puro. Idempotente: linhas já em texto puro são puladas. Conexão e APP_KEY são as ativas no momento da invocação (sobrescreva via env vars no comando para apontar para outro ambiente).';

    private const FIELDS = ['nome', 'CPF', 'telefone', 'numero_documento'];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $connection = config('database.default');
        $this->info(sprintf(
            'Conexão: %s@%s/%s%s',
            config("database.connections.$connection.username"),
            config("database.connections.$connection.host"),
            config("database.connections.$connection.database"),
            $dryRun ? ' [DRY RUN]' : ''
        ));

        $rows = DB::table('sindico')->select(array_merge(['id'], self::FIELDS))->get();
        $this->info("Total de linhas: {$rows->count()}");

        $updated = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($rows->count());
        $bar->start();

        foreach ($rows as $row) {
            $changes = [];

            foreach (self::FIELDS as $field) {
                $value = $row->$field;

                if ($value === null || $value === '') {
                    continue;
                }

                try {
                    $decrypted = Crypt::decrypt($value);
                } catch (DecryptException $e) {
                    // já é texto puro (ou veio de chave diferente) — não toca
                    continue;
                }

                if (!is_string($decrypted)) {
                    $this->warn("\nLinha {$row->id}, campo {$field}: decriptou para tipo inesperado, pulando manualmente.");
                    $failed++;
                    continue;
                }

                $changes[$field] = $decrypted;
            }

            if (empty($changes)) {
                $skipped++;
            } else {
                if (!$dryRun) {
                    DB::table('sindico')->where('id', $row->id)->update($changes);
                }
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info(sprintf(
            '%sAtualizadas: %d | Já em texto puro: %d | Falhas: %d',
            $dryRun ? '[DRY RUN] ' : '',
            $updated,
            $skipped,
            $failed
        ));

        return self::SUCCESS;
    }
}
