<?php

namespace App\Jobs;

use App\Repositories\Feed\FeedRepository;
use App\Repositories\Feed\FeedRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFeedSize implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $feedId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $feedId)
    {
        $this->feedId = $feedId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FeedRepositoryInterface $feedRepository)
    {
        // get feed's subscribers and define is it huge or not
        $feedSubscribersCount = $feedRepository->getFeedSubscribersCount(
            $this->feedId
        );
        $feed = Feed::find($this->feedId);
        // TODO :: use repository
        if ($feedSubscribersCount > 100000) {
            // TODO :: read from config
            if (!$feed->is_huge) {
                $feed->is_huge = true;
                $feed->save();
            }
        } else {
            if ($feed->is_huge) {
                $feed->is_huge = false;
                $feed->save();
            }
        }
    }
}
