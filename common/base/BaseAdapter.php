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
        $this->client = new Client();
        $this->url = $this->getApiUrl();
    }

    protected abstract function getApiUrl(): string;

    public abstract function getAll(bool $assoc = false): array;
}