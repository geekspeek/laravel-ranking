<?php

/**
 * 
 */

namespace geekspeek\Ranking;

use Illuminate\Support\ServiceProvider;

/**
 * 
 */
class RankingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ranking', function ($app) {
            return new RankingManage($app);
        });
    }
}
