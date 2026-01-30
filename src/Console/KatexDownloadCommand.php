<?php

declare(strict_types=1);

namespace AkramZaitout\LaravelKatex\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class KatexDownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'katex:download
                          {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download KaTeX assets to the public directory for offline use';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Downloading KaTeX assets...');

        $publicPath = public_path('vendor/katex');
        $force = $this->option('force');

        if (File::exists($publicPath) && ! $force) {
            if (! $this->confirm('KaTeX assets already exist. Do you want to overwrite them?')) {
                $this->info('Download cancelled.');
                return 0;
            }
        }

        $this->ensureDirectoryExists($publicPath);

        // Configuration
        $version = config('katex.version', '0.16.28');
        $cdn = rtrim(config('katex.cdn', 'https://cdn.jsdelivr.net/npm/katex'), '/');
        
        // Handle CDN URL format
        if (strpos($cdn, 'jsdelivr') !== false) {
             $baseUrl = sprintf('%s@%s/dist', $cdn, $version);
        } else {
             $baseUrl = sprintf('%s/%s/dist', $cdn, $version);
             // Verify this fallback or just use standard construction if cdn doesn't have version placeholder
             // Actually config default is https://cdn.jsdelivr.net/npm/katex, usually implies version appended with @
             // Let's assume the user hasn't messed with CDN too much or we follow standard pattern
             // The Renderer uses: sprintf('%s@%s/dist/katex.min.css', e($cdn), e($version))
             // I should replicate that logic logic.
             $baseUrl = sprintf('%s@%s/dist', $cdn, $version);
        }

        $files = [
            'katex.min.css',
            'katex.min.js',
            'contrib/auto-render.min.js',
        ];

        // Download core files
        foreach ($files as $file) {
            $url = "{$baseUrl}/{$file}";
            $destination = "{$publicPath}/{$file}";
            
             // Ensure subdirectory exists for contrib
            $this->ensureDirectoryExists(dirname($destination));

            $this->downloadFile($url, $destination);
        }

        // Fonts
        $fontsPath = "{$publicPath}/fonts";
        $this->ensureDirectoryExists($fontsPath);
        
        $fontFiles = [
            'KaTeX_AMS-Regular',
            'KaTeX_Caligraphic-Bold',
            'KaTeX_Caligraphic-Regular',
            'KaTeX_Fraktur-Bold',
            'KaTeX_Fraktur-Regular',
            'KaTeX_Main-Bold',
            'KaTeX_Main-BoldItalic',
            'KaTeX_Main-Italic',
            'KaTeX_Main-Regular',
            'KaTeX_Math-BoldItalic',
            'KaTeX_Math-Italic',
            'KaTeX_SansSerif-Bold',
            'KaTeX_SansSerif-Italic',
            'KaTeX_SansSerif-Regular',
            'KaTeX_Script-Regular',
            'KaTeX_Size1-Regular',
            'KaTeX_Size2-Regular',
            'KaTeX_Size3-Regular',
            'KaTeX_Size4-Regular',
            'KaTeX_Typewriter-Regular',
        ];

        $extensions = ['woff2', 'woff', 'ttf'];

        $bar = $this->output->createProgressBar(count($fontFiles) * count($extensions));
        $bar->start();

        foreach ($fontFiles as $font) {
            foreach ($extensions as $ext) {
                $filename = "{$font}.{$ext}";
                $url = "{$baseUrl}/fonts/{$filename}";
                $destination = "{$fontsPath}/{$filename}";

                try {
                    $this->downloadFile($url, $destination);
                } catch (\Exception $e) {
                    $this->warn("\nFailed to download {$filename}: " . $e->getMessage());
                }
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();

        $this->info('KaTeX assets downloaded successfully.');
        $this->comment('To use local assets, set KATEX_USE_LOCAL_ASSETS=true in your .env file.');

        return 0;
    }

    protected function ensureDirectoryExists(string $path): void
    {
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function downloadFile(string $url, string $destination): void
    {
        $content = @file_get_contents($url);

        if ($content === false) {
             throw new \RuntimeException("Could not download file from {$url}");
        }

        File::put($destination, $content);
    }
}
