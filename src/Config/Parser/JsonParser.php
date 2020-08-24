<?php

namespace App\Config\Parser;

use App\Config\Exceptions\InvalidFileException;

class JsonParser extends ParserInterface
{
    public function parse(string $filePath): array
    {
        $content = json_decode(file_get_contents($filePath), 1);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $content;
        }

        throw new InvalidFileException("Invalid JSON file: $filePath");
    }
}
