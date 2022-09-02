<?php

namespace App\Observers;

use App\Jobs\Crawler\ProcessFeed;
use App\Jobs\ProcessFeedSize;
use App\Models\FeedUser;

class FeedUserObserver
{
    /**
     * Handle the FeedUser "saved" event.
     *
     * @param  \App\Models\FeedUser  $feedUser
     * @return void
     */
    public function saved(FeedUser $feedUser)
    {
        ProcessFeedSize::dispatch($feedUser->feed_id);
    }

    /**
     * Handle the FeedUser "saved" event.
     *
     * @param  \App\Models\FeedUser  $feedUser
     * @return void
     */
    public function deleted(FeedUser $feedUser)
    {
        ProcessFeedSize::dispatch($feedUser->feed_id);
    }
}
