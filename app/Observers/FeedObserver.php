<?php

namespace App\Observers;

use App\Jobs\Crawler\ProcessFeed;
use App\Models\Feed;

class FeedObserver
{
    /**
     * Handle the Feed "saved" event.
     *
     * @param Feed $feed
     * @return void
     */
    public function saved(Feed $feed)
    {
        // send data to the crawler service if it is enable in config
        ProcessFeed::dispatchBasedOnConfig($feed->id);
    }
}
