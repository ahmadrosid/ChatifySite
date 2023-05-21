<?php

namespace App\Helpers;

class ServerEvent
{
    public static function send($data, $new_line = "\n")
    {
        echo "{$data}{$new_line}";
        ob_flush();
        flush();
    }
}
