<?php

namespace LGL\Ranking\Facades;

use Illuminate\Support\Facades\Facade;
use LGL\Ranking\RankingManage;


/**
 * Class Ranking

 *
 * @author 
 */
class Ranking extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ranking';
    }
}
