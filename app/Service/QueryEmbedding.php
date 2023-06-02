<?php

namespace App\Service;

use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class QueryEmbedding
{

    public function getQueryEmbedding($question): array
    {
        $result = OpenAI::embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $question,
        ]);

        if (count($result['data']) == 0) {
            throw new Exception("Failed to generated query embedding!");
        }

        return $result['data'][0]['embedding'];
    }

    public function askQuestionStreamed($context, $question)
    {
        $system_template = "
        Use the following pieces of context to answer the users question. 
        If you don't know the answer, just say that you don't know, don't try to make up an answer.
        ----------------
        {context}
        ";
        $system_prompt = str_replace("{context}", $context, $system_template);

        return OpenAI::chat()->createStreamed([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.8,
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                ['role' => 'user', 'content' => $question],
            ],
        ]);
    }
}
