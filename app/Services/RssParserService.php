<?php

namespace App\Services;

use App\Jobs\AddNewArticle;
use App\Models\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class RssParserService extends Command
{
    private $url;
    private $method;
    private $supported_image = [
        'gif',
        'jpg',
        'jpeg',
        'png'
    ];

    private function checkExtImage($str): string
    {
        $ext = strtolower(pathinfo($str, PATHINFO_EXTENSION));

        if (in_array($ext, $this->supported_image)) {
            return $str;
        }

        return '';
    }

    public function getNews()
    {
        try {
            $client = new Client;

            $response = $client->get('http://static.feed.rbc.ru/rbc/logical/footer/news.rss', [
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $this->url = $stats->getEffectiveUri();
                    $this->method = $stats->getRequest()->getMethod();
                }
            ]);

            $data = $response->getBody()->getContents();

            $feed = simplexml_load_string($data);

            foreach ($feed->channel->item as $item) {
                $title = strip_tags(trim($item->title)) ?? '';
                $link = strip_tags(trim($item->link)) ?? '';
                $description = strip_tags(trim($item->description)) ?? '';
                $date = ($item->pubDate) ? date("Y-m-d H:i:s", strtotime($item->pubDate)) : '';
                $author = strip_tags(trim($item->author)) ?? '';
                $img = $item->enclosure ? strip_tags(trim($this->checkExtImage($item->enclosure->attributes()->{'url'}))) : '';

                try {
                    AddNewArticle::dispatch($title, $link, $description, $date, $author, $img);

                } catch (QueryException $e) {
                    //var_dump($e->errorInfo);
                }
            }

            return (true);

        } catch (ClientException $e) {
            $response = $e->getResponse();
            echo $e->getMessage();

        } finally {
            $log = Log::create([
                'date' => Carbon::now(),
                'method' => $this->method,
                'url' => $this->url,
                'status_code' => $response->getStatusCode(),
                'body' => $response->getBody()
            ]);
        }
    }
}
