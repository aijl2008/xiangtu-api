<?php
/**
 * Created by PhpStorm.
 * User: artron
 * Date: 2018/11/19
 * Time: 20:36
 */

namespace App\Http\Controllers\My;


use App\Http\Controllers\Controller;
use App\Service\Statistics;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    function index()
    {
        $statistics = (new Statistics())->make(Auth::guard('wechat')->user());
        return view('my.statistics.index')
            ->with('played_number', $statistics['played_number'])
            ->with('be_followed_number', $statistics['be_followed_number'])
            ->with('total_played_number', $statistics['total_played_number'])
            ->with('follower_date', json_encode(array_keys($statistics['followers'])))
            ->with('follower_value', json_encode(array_values($statistics['followers'])))
            ->with('play_date', json_encode(array_keys($statistics['play'])))
            ->with('play_value', json_encode(array_values($statistics['play'])))
            ->with('upload_date', json_encode(array_keys($statistics['upload'])))
            ->with('upload_value', json_encode(array_values($statistics['upload'])));
    }
}