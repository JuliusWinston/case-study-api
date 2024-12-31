<?php

namespace App\Http\Controllers\Api;

use App\Services\NewsScraperService;
use App\Http\Controllers\Controller;

class NewsScrapperController extends Controller
{
    private $newsScraper;

    public function __construct(NewsScraperService $newsScraper)
    {
        $this->newsScraper = $newsScraper;
    }

    public function fetchArticles()
    {
        $articles = $this->newsScraper->fetchArticles();
        return response()->json(['data' => $articles, 'count' => count($articles)]);
    }
}
