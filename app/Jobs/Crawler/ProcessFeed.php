<?php

namespace App\Jobs\Crawler;

use App\Jobs\Traits\CrawlerDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFeed implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        CrawlerDispatcher;

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
    public function handle()
    {
        //
    }
}
