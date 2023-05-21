<?php

namespace App\Service;

class Tokenizer
{
    public function tokenize($text, $chunk)
    {
        $normalizedText = preg_replace("/\n+/", "\n", $text);
        $words = explode(' ', $normalizedText);
        $words = array_filter($words);
        // return $words;
        $result = array_chunk($words, $chunk);
        return $result;
    }
}
