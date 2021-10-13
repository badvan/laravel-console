<?php

namespace App\Console\Commands;

use App\Services\RssParserService;
use Illuminate\Console\Command;


class ParserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rss парсер RBC News';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RssParserService $rssParserService)
    {
        if ($rssParserService->getNews())
            $this->info('Все новости успешно сохранены.');

        return 0;
    }
}
