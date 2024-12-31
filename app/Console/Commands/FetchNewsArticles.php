<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsScraperService;

class FetchNewsArticles extends Command
{
    protected $signature = 'news:fetch';

    protected $description = 'Fetch and store news articles from  NewsAPI, New York Times (Free Tier), and The Guardian (Free Tier).';

    private $newsScraper;

    public function __construct(NewsScraperService $newsScraper)
    {
        parent::__construct();
        $this->newsScraper = $newsScraper;
    }

    public function handle()
    {
        $this->info('Fetching news articles...');
        $this->newsScraper->fetchAndStoreArticles();
        $this->info('News articles fetched and stored successfully.');
    }
}
