<?php

namespace App;

use App\Config\ParserFactory;
use App\Config\Exceptions\MissingFileException;
use App\Config\Exceptions\InvalidKeyException;

class Config
{
    private string $baseDirectory;

    private ParserInterface $parser;

    private array $config = [];

    public function __construct(string $baseDirectory, ?ParserFactory $parserFactory = null)
    {
        $this->baseDirectory = $baseDirectory;

        if ($parserFactory) {
            $this->parserFactory = $parserFactory;
        } else {
            $this->parserFactory = new ParserFactory();
        }
    }

    public function load(string ...$filePaths)
    {
        foreach ($filePaths as $filePath) {
            $fullFilePath = $this->baseDirectory . DIRECTORY_SEPARATOR . $filePath;
            if (!file_exists($fullFilePath)) {
                throw new MissingFileException("File is missing: $fullFilePath");
            }

            $extension = pathinfo($filePath)['extension'];
            $parser = $this->parserFactory::getParser($extension);

            $this->config = $this->merge(
                $parser->parse($this->baseDirectory . DIRECTORY_SEPARATOR . $filePath),
                $this->config
            );
        }
    }

    public function merge(array $config, array $allConfig): array
    {
        return array_replace_recursive($allConfig, $config);
    }

    public function getAll(): ?array
    {
        return $this->config;
    }

    /**
     * @param string $key
     *
     * @return string|array
     *
     * @throws InvalidKeyException
     */
    public function get(string $key)
    {
        $keyArray = explode(".", $key);

        $section = $this->config;

        foreach ($keyArray as $key) {
            if (!isset($section[$key])) {
                throw new InvalidKeyException("Key does not exist: $key");
            }

            $section = $section[$key];
        }

        return $section;
    }
}
