<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Http;

class NewsScraperService
{
    public function fetchAndStoreArticles()
    {
        $articles = $this->fetchArticles();

        foreach ($articles as $article) {
            Article::updateOrCreate(
                [
                    'title' => $article['title'],'source' => $article['source']
                ],
                [
                    'author'       => $article['author'],
                    'category'     => $article['category'],
                    'content'      => $article['content'],
                    'published_at' => $article['published_at'],
                    'thumbnail'    => $article['thumbnail'],
                ]
            );
        }
    }

    /**
     * Fetch articles from NewsAPI, OpenNews, and The Guardian.
     *
     * @return array
     */
    public function fetchArticles(): array
    {
        $newsApiArticles = $this->fetchFromNewsApi();
        $guardianArticles = $this->fetchFromTheGuardian();
        $newYorkTimessArticles = $this->fetchFromNweYorktimes();

        // Combine articles from all sources
        return array_merge($newsApiArticles, $newYorkTimessArticles, $guardianArticles);
    }

    /**
     * Fetch articles from NewsAPI.
     *
     * @return array
     */
    private function fetchFromNewsApi(): array
    {
        $url = 'https://newsapi.org/v2/everything?q=general&source=us&from=2024-12-01&to=2024-12-20&page=1&pageSize=80&apiKey=' . config('services.newsapi.key');

        $response = Http::timeout(60)->get($url);

        if ($response->successful()) {
            return collect($response->json('articles'))->map(function ($article) {
                return [
                    'title'       => $article['title'],
                    'content'     => $article['content'],
                    'source'      => $article['source']['name'] ?? '',
                    'author'      => $article['author'] ?? 'Dev Winston',
                    'thumbnail' => $article['urlToImage'] ?? '',
                    'category'    => 'General',
                    'published_at'=> $article['publishedAt'],
                ];
            })->toArray();
        }

        return [];
    }


    /**
     * Fetch articles from New York Times.
     *
     * @return array
     */
    private function fetchFromNweYorktimes(): array
    {
        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json?pageSize=10&api-key=' . config('services.nytimesapi.key');

        $response = Http::timeout(60)->get($url);

        if ($response->successful()) {
            return collect($response->json('response.docs'))->map(function ($article) {
                return [
                    'title'       => $article['headline']['main'],
                    'content'     => $article['snippet'] . ' ' . $article['lead_paragraph'],
                    'source'      => $article['source'] ?? 'New York Times',
                    'author'      => $article['author'] ?? 'Dev Winston',
                    'thumbnail' => '',
                    'category'    => $article['section_name'] ?? 'General',
                    'published_at'=> $article['pub_date'],
                ];
            })->toArray();
        }

        return [];
    }

    /**
     * Fetch articles from The Guardian.
     *
     * @return array
     */
    private function fetchFromTheGuardian(): array
    {
        $url = 'https://content.guardianapis.com/search?pageSize=10&api-key=' . config('services.guardian.key');

        $response = Http::timeout(60)->get($url);

        if ($response->successful()) {
            return collect($response->json('response.results'))->map(function ($article) {
                return [
                    'title'       => $article['webTitle'],
                    'content'     => $article['webUrl'], // Use title as content if content is not available
                    'source'      => 'The Guardian',
                    'author'      => 'Dev Winston', // The Guardian API does not provide author info in the free plan
                    'thumbnail' => '',
                    'category'    => $article['sectionName'] ?? 'General',
                    'published_at'=> $article['webPublicationDate'],
                ];
            })->toArray();
        }

        return [];
    }
}
