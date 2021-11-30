<?php

namespace geekspeek\Ranking;

use Illuminate\Support\Manager;

/**
 * Class RankingManage
 *
 */
class RankingManage extends Manager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new filesystem manager instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getDefaultDriver()
    {
        return 'redis';
    }

    public function createRedisDriver()
    {
        return new RankingAdapter('ranking');
    }
}
