<?php

namespace App\Http\Controllers;

use App\Helpers\ServerEvent;
use App\Models\Chat;
use App\Models\EmbedCollection;
use App\Models\Message;
use App\Service\QueryEmbedding;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

    protected QueryEmbedding $query;

    public function __construct(QueryEmbedding $query)
    {
        $this->query = $query;
    }

    public function index($id)
    {
        $chat = Chat::with('embed_collection')->find($id);
        return view('welcome', [
            'source' => 'chatbot',
            'chat' => $chat,
            'embed_collection' => $chat->embed_collection->toArray(),
            'messages' => Message::query()->where('chat_id', $chat->id)->get()
        ]);
    }

    public function store(Request $request)
    {
        return response()->stream(function () use ($request) {
            try {
                $chat_id = $request->chat_id;
                $chat = Chat::with('embed_collection')->find($chat_id);
                $question = $request->question;
                $queryVectors = $this->query->getQueryEmbedding($question);
                $vector = json_encode($queryVectors);
                $result = DB::table('embeddings')
                    ->select("text")
                    ->selectSub("embedding <=> '{$vector}'::vector", "distance")
                    ->where('embed_collection_id', $chat->embed_collection->id)
                    ->orderBy('distance', 'asc')
                    ->limit(2)
                    ->get();
                $context = collect($result)->map(function ($item) {
                    return $item->text;
                })->implode("\n");

                $stream = $this->query->askQuestionStreamed($context, $question);
                $resultText = "";
                foreach ($stream as $response) {
                    $text = $response->choices[0]->delta->content;
                    $resultText .= $text;
                    if (connection_aborted()) {
                        break;
                    }
                    ServerEvent::send($text, "");
                }
                Message::insert([[
                    'chat_id' => $chat_id,
                    'role' => Message::ROLE_USER,
                    'content' => $question
                ], [
                    'chat_id' => $chat_id,
                    'role' => Message::ROLE_BOT,
                    'content' => $resultText
                ]]);
            } catch (Exception $e) {
                Log::error($e);
                ServerEvent::send("");
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
