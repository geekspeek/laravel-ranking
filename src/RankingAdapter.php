<?php

namespace geekspeek\Ranking;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class RankingAdapter
{
    /**
     * @var string
     */
    private $ranking;

    /**
     * @var \Illuminate\Redis\Connections\Connection
     */
    private $redis;

    /**
     * RankingService constructor.
     * @param string $ranking
     * @param string $redis
     */
    public function __construct($redis)
    {
        $this->redis = Redis::connection($redis);
    }

    public function key($ranking)
    {
        $this->ranking = $ranking . ':';
        return $this;
    }

    /**
     * @param string|int $identity
     * @param int $scores
     * @return mixed
     */
    public function addScores($identity, $scores)
    {
        $key = $this->ranking . date('Ymd');
        return $this->redis->zincrby($key, $scores, $identity);
    }

    /**
     * @return mixed
     */
    public function getYesterdayTop10()
    {
        // $date = Carbon::now()->subDays(1)->format('Ymd');
        $date = Carbon::now()->format('Ymd');
        return $this->getOneDayRankings($date, 0, 9);
    }

    /**
     * @return mixed
     */
    public function getCurrentMonthTop10()
    {
        $dates = static::getCurrentMonthDates();
        return $this->getMultiDaysRankings($dates, 'rank:current_month', 0, 9);
    }

    /**
     * @return mixed
     */
    public function getCurrentWeekTop10()
    {
        $dates = static::getCurrentWeekDates();
        return $this->getMultiDaysRankings($dates, 'rank:current_week', 0, 9);
    }

    /**
     * @param string $date 20170101
     * @param int $start
     * @param int $stop
     * @return array
     */
    public function getOneDayRankings($date, $start, $stop)
    {
        $key = $this->ranking . $date;
        return $this->redis->zrevrange($key, $start, $stop, ['withscores' => true]);
    }

    /**
     * @param array $dates ['20170101','20170102']
     * @param string $outKey
     * @param int $start
     * @param int $stop
     * @return mixed
     */
    public function getMultiDaysRankings($dates, $outKey, $start, $stop)
    {
        $keys = array_map(function ($date) {
            return $this->ranking . $date;
        }, $dates);
        $weights = array_fill(0, count($keys), 1);
        $this->redis->zunionstore($outKey, $keys, $weights);
        return $this->redis->zrevrange($outKey, $start, $stop, ['withscores' => true]);
    }

    /**
     * 
     * @return array
     */
    public static function getCurrentWeekDates()
    {
        $dt = Carbon::now();
        $dt->startOfWeek();
        $dates = [];
        for ($day = 1; $day <= 7; $day++) {
            $dates[] = $dt->format('Ymd');
            $dt->addDay();
        }
        return $dates;
    }

    /**
     * 
     * @return array
     */
    public static function getCurrentMonthDates()
    {
        $dt = Carbon::now();
        $days = $dt->daysInMonth;
        $dates = [];
        for ($day = 1; $day <= $days; $day++) {
            $dt->day = $day;
            $dates[] = $dt->format('Ymd');
        }
        return $dates;
    }
}
