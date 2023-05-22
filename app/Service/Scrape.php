<?php

namespace App\Service;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\DomCrawler\Crawler;

class Scrape
{
    public $title;
    private $converter;

    public function __construct()
    {
        $this->converter = new HtmlConverter(array('strip_tags' => true, 'strip_placeholder_links' => true));
    }

    private function removeHrefAttribute($htmlString)
    {
        $pattern = '/<a\b[^>]*\bhref\s*=\s*"[^"]*"[^>]*>/i';
        $replacement = '<a>';
        $result = preg_replace($pattern, $replacement, $htmlString);
        return $result;
    }

    private function cleanHtml($htmlContent)
    {
        // Clean this tags: <style> <script> <span> <footer> <aside> <nav>
        $cleanHtml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $htmlContent);
        $cleanHtml = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<svg\b[^>]*>(.*?)<\/svg>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<picture\b[^>]*>(.*?)<\/picture>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<form\b[^>]*>(.*?)<\/form>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<footer\b[^>]*>(.*?)<\/footer>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', '', $cleanHtml);
        $cleanHtml = preg_replace('/<span[^>]*>(.*?)<\/span>/is', '$1', $cleanHtml);
        $cleanHtml = $this->removeHrefAttribute($cleanHtml);
        return trim($cleanHtml);
    }

    private function reverseLTGT($input)
    {
        $output = str_replace('&lt;', '<', $input);
        $output = str_replace('&gt;', '>', $output);
        return $output;
    }

    public function handle($url)
    {
        $url = $url;
        $client = new Client();
        $response = $client->get($url,  [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept-Encoding' => 'gzip',
            ],
        ]);

        $htmlContent = $response->getBody()->getContents();
        $cleanHtml = $this->cleanHtml($htmlContent);

        $this->converter->getEnvironment()->addConverter(new PreTagConverter());
        $markdownContent = $this->converter->convert($cleanHtml);
        $markdownContent = $this->reverseLTGT($markdownContent);
        // Usefull for debugging.
        // Log::info($cleanHtml);
        // Log::info($markdownContent);
        try {
            $dom = new Crawler($htmlContent);
            $this->title = $dom->filter('title')->first()->text();
        } catch (\Exception $e) {
            $this->title = substr($markdownContent, 0, strpos($markdownContent, "\n"));
        }

        return $markdownContent;
    }
}
