<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/23
 * Time: 下午4:27
 */

namespace App\Service;


use App\Models\Log;
use Illuminate\Support\Facades\DB;

class Statistics
{
    function make($user, $url = false)
    {
        $played_number = Log::query()
            ->where('action', '播放')
            ->where('to_user_id', $user->id)
            ->whereBetween('created_at', [
                date('m-d', time()),
                date('m-d', time() + 3600 * 24)
            ])->count('id');
        $be_followed_number = Log::query()
            ->where('action', '关注')
            ->where('to_user_id', $user->id)
            ->whereBetween('created_at', [
                date('m-d', time()),
                date('m-d', time() + 3600 * 24)
            ])->count('id');
        $total_played_number = Log::query()
            ->where('action', '播放')
            ->where('to_user_id', $user->id)
            ->count('id');

        if ($url) {
            return ['played_number' => $played_number
                , 'be_followed_number' => $be_followed_number
                , 'total_played_number' => $total_played_number
                , 'follower' => "https://www.xiangtu.net.cn/statistics/follower/".$user->id
                , 'play' => "https://www.xiangtu.net.cn/statistics/play/".$user->id
                , 'upload' => "https://www.xiangtu.net.cn/statistics/upload/".$user->id
            ];
        }
        $followers = $this->fill();
        foreach (Log::query()
                     ->where('action', '关注')
                     ->where('to_user_id', $user->id)
                     ->whereBetween('created_at', [
                         date('m-d', time() - 3600 * 24 * 8),
                         date('m-d', time() + 3600 * 24)
                     ])
                     ->groupBy(DB::raw('substr(created_at,1,10)'))
                     ->select(DB::raw(
                         'substr(created_at,1,10) as date, 
                count(id) as number'
                     ))->get() as $item) {
            if (array_key_exists($item->date, $followers)) {
                $followers[$item->date] = $item->number;
            }
        }
        $play = $this->fill();
        foreach (Log::query()
                     ->where('action', '播放')
                     ->where('to_user_id', $user->id)
                     ->whereBetween('created_at', [
                         date('m-d', time() - 3600 * 24 * 8),
                         date('m-d', time() + 3600 * 24)
                     ])
                     ->groupBy(DB::raw('substr(created_at,1,10)'))
                     ->select(DB::raw(
                         'substr(created_at,1,10) as date, 
                count(id) as number'
                     ))->get() as $item) {
            if (array_key_exists($item->date, $play)) {
                $play[$item->date] = $item->number;
            }
        }

        $upload = $this->fill();
        foreach (Log::query()
                     ->where('action', '上传视频')
                     ->where('from_user_id', $user->id)
                     ->whereBetween('created_at', [
                         date('m-d', time() - 3600 * 24 * 8),
                         date('m-d', time() + 3600 * 24)
                     ])
                     ->groupBy(DB::raw('substr(created_at,1,10)'))
                     ->select(DB::raw(
                         'substr(created_at,1,10) as date, 
                count(id) as number'
                     ))->get() as $item) {
            if (array_key_exists($item->date, $upload)) {
                $upload[$item->date] = $item->number;
            }
        }


        return ['played_number' => $played_number
            , 'be_followed_number' => $be_followed_number
            , 'total_played_number' => $total_played_number
            , 'followers' => $followers
            , 'play' => $play
            , 'upload' => $upload];
    }


    function fill()
    {
        $ret = [];
        for ($i = time() - 3600 * 24 * 6; $i <= time(); $i = $i + 3600 * 24) {
            $ret[date('m-d', $i)] = 0;
        }
        return $ret;
    }
}
