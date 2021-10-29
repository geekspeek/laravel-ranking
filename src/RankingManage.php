<?php

namespace geekspeek\Ranking;

/**
 * Class RankingManage
 *
 */
class RankingManage
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

    /**
     * 
     * @param string $ranking
     * @return RankingAdapter
     */
    public function get($ranking)
    {
        return new RankingAdapter($ranking, $this->app['config']["services.ranking.redis"] ?? 'default');
    }
}
