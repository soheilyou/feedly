<?php

namespace App\Providers;

use App\Models\Feed;
use App\Models\FeedUser;
use App\Observers\FeedObserver;
use App\Observers\FeedUserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Feed::observe(FeedObserver::class);
        FeedUser::observe(FeedUserObserver::class);
    }
}
