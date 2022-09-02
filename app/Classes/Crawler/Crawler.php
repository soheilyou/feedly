<?php

namespace App\Classes\Crawler;

use App\Classes\Crawler\Exceptions\CrawlerException;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Crawler
{
    /**
     * @var Repository|Application|mixed
     */
    private $host;
    /**
     * @var Repository|Application|mixed
     */
    private $token;
    /**
     * @var PendingRequest
     */
    protected $request;

    /**
     * Crawler constructor.
     */
    public function __construct()
    {
        $this->host = config("crawler.host");
        $this->token = config("crawler.token");
        $this->request = Http::withHeaders([
            "accept" => "application/json",
        ])->withToken($this->token);
    }

    /**
     * @param $url
     * @return string
     */
    protected function getFullUrl($url): string
    {
        return "$this->host/$url";
    }

    /**
     * @param $httpMethod
     * @param $url
     * @param $data
     * @return mixed
     * @throws CrawlerException
     */
    protected function makeRequest($httpMethod, $url, $data)
    {
        $response = $this->request->{$httpMethod}(
            $this->getFullUrl($url),
            $data
        );
        if ($response->failed()) {
            throw new CrawlerException($response->body());
        }
        return $response;
    }

    /**
     * @param $url
     * @param array|null $query
     * @return mixed
     * @throws CrawlerException
     */
    protected function get($url, array $query = null)
    {
        return $this->makeRequest("get", $url, $query);
    }

    /**
     * @param $url
     * @param array|null $query
     * @return mixed
     * @throws CrawlerException
     */
    protected function getJson($url, array $query = null)
    {
        return $this->get($url, $query)->json();
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     * @throws CrawlerException
     */
    protected function post($url, array $data = [])
    {
        return $this->makeRequest("post", $url, $data);
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     * @throws CrawlerException
     */
    protected function postJson($url, array $data = [])
    {
        return $this->post($url, $data)->json();
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     * @throws CrawlerException
     */
    protected function delete($url, array $data = [])
    {
        return $this->makeRequest("delete", $url, $data);
    }

    /**
     * @param int $id
     * @param string $url
     * @return mixed
     * @throws CrawlerException
     */
    public function addFeed(int $id, string $url)
    {
        return $this->postJson("api/services/feedly/add-new-feed", [
            "feed" => [
                "id" => $id,
                "url" => $url,
            ],
        ]);
    }
}
