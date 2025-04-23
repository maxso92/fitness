<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Sitemap\SitemapService;
use Exception;

class SitemapGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate sitemap';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $this->info('Start generating sitemeap');
            $service = new SitemapService();
            $service->generate();
            $this->info('Sitemap generation completed successfully');
        } catch (Exception $e) {
            $this->error('Sitemap generation completed with an error');
            dd($e->getMessage());
        }
    }
}
