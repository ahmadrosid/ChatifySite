<?php

namespace App\Http\Controllers;

use App\Helpers\ServerEvent;
use App\Models\Chat;
use App\Models\EmbedCollection;
use App\Models\Embedding;
use App\Service\QueryEmbedding;
use App\Service\Scrape;
use App\Service\Tokenizer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmbeddingController extends Controller
{
    protected Scrape $scraper;
    protected Tokenizer $tokenizer;
    protected QueryEmbedding $query;

    public function __construct(Scrape $scrape, Tokenizer $tokenizer, QueryEmbedding $query)
    {
        $this->scraper = $scrape;
        $this->tokenizer = $tokenizer;
        $this->query = $query;
    }

    public function store(Request $request)
    {
        $url = $request->link;
        return response()->stream(function () use ($url) {
            try {
                ServerEvent::send("Start crawling: {$url}");
                $markdown = $this->scraper->handle($url);
                $tokens = $this->tokenizer->tokenize($markdown, 256);

                $title = $this->scraper->title;
                $count = count($tokens);
                $total = 0;
                $collection = EmbedCollection::create([
                    'name' => $title,
                    'meta_data' => json_encode([
                        'title' => $title,
                        'url' => $url,
                    ]),
                ]);

                foreach ($tokens as $token) {
                    $total++;
                    $text = implode("\n", $token);
                    $vectors = $this->query->getQueryEmbedding($text);
                    Embedding::create([
                        'embed_collection_id' => $collection->id,
                        'text' => $text,
                        'embedding' => json_encode($vectors)
                    ]);
                    ServerEvent::send("Indexing: {$title}, {$total} of {$count} elements.");

                    if (connection_aborted()) {
                        break;
                    }
                }
                sleep(1);
                $chat = Chat::create(['embed_collection_id' => $collection->id]);
                ServerEvent::send(route("chat.show", $chat->id));
            } catch (Exception $e) {
                Log::error($e);
                ServerEvent::send("Embedding failed");
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
