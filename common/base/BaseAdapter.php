<?php

namespace common\base;

use GuzzleHttp\Client;

abstract class BaseAdapter
{
    protected $url;
    protected $client;

    public function __construct()
    {
        require ROOT . 'lib/vendor/autoload.php';
        $this->url = $this->getApiUrl();
        $this->client = new Client();
    }

    protected abstract function getApiUrl(): string;

    public abstract function getAll(bool $assoc = false): array;
}