<?php

namespace App\Config;

use App\Config\Parser\ParserInterface;
use App\Config\Parser\JsonParser;
use App\Config\Parser\YAMLParser;

class ParserFactory
{
    static function getParser (string $extension): ParserInterface
    {
        if (strtolower($extension == "json")) {
            return new JsonParser();
        }

        if (strtolower($extension == "yml")) {
            return new YAMLParser();
        }
    }
}
