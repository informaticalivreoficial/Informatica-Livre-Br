<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateMediaToR2 extends Command
{
    protected $signature = 'media:migrate-to-r2
                            {--disk= : Disco de destino (padrão: disco padrão do projeto)}
                            {--source= : Diretório local de origem (padrão: storage/app/public)}
                            {--force : Sobrescreve arquivos já existentes no destino}';

    protected $description = 'Envia as mídias locais de storage/app/public para o disco de destino (R2)';

    public function handle(): int
    {
        $diskName = $this->option('disk') ?: config('filesystems.default');
        $source   = $this->option('source') ?: storage_path('app/public');
        $force    = (bool) $this->option('force');

        if (!is_dir($source)) {
            $this->error("Diretório de origem não encontrado: {$source}");

            return self::FAILURE;
        }

        $disk  = Storage::disk($diskName);
        $files = File::allFiles($source);

        if (empty($files)) {
            $this->warn('Nenhum arquivo encontrado para migrar.');

            return self::SUCCESS;
        }

        $this->info("Migrando " . count($files) . " arquivo(s) para o disco [{$diskName}]...");

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $uploaded = 0;
        $skipped  = 0;
        $failed   = 0;

        foreach ($files as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());

            $ignored = str_starts_with($relative, 'cache/')
                || str_starts_with($relative, 'livewire-tmp/')
                || str_starts_with($relative, 'infolivre/')
                || str_starts_with($relative, '.');

            if ($ignored) {
                $skipped++;
                $bar->advance();

                continue;
            }

            try {
                if (!$force && $disk->exists($relative)) {
                    $skipped++;
                    $bar->advance();

                    continue;
                }

                $stream = fopen($file->getRealPath(), 'r');

                if ($stream === false) {
                    $failed++;
                    $bar->advance();

                    continue;
                }

                $disk->writeStream($relative, $stream);

                if (is_resource($stream)) {
                    fclose($stream);
                }

                $uploaded++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Falha ao enviar {$relative}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Enviados: {$uploaded}");
        $this->line("Ignorados: {$skipped}");
        $this->line("Falhas: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
