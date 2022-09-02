<?php

namespace App\Jobs\Traits;

trait CrawlerDispatcher
{
    public static string $crawlerQueue = "crawler";

    public static function dispatchBasedOnConfig(...$args)
    {
        /**
         * check config first
         */
        if (config("crawler.enable_connection")) {
            self::dispatch(...$args)->onQueue(self::$crawlerQueue);
        }
    }
}
