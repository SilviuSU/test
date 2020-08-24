<?php

namespace App\Config\Parser;

use App\Config\Exceptions\InvalidFileException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YAMLParser extends ParserInterface
{
    public function parse(string $filePath): array
    {
        try {
            return Yaml::parseFile($filePath);
        } catch (ParseException $exception) {
            throw new InvalidFileException("Invalid JSON file: $filePath");
        }
    }
}
