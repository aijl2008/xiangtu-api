<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/22
 * Time: 下午1:19
 */

namespace App\Service;


use App\Models\Task;
use App\Models\Vod;
use App\Models\Wechat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class Video
{
    /**
     * @param Wechat|null $Wechat 当前登录用户
     * @param int $classification 分类
     * @param int $wechat_id 查询指定用户
     * @param int $take 每页数
     * @param bool $simple 是否启用简单分页
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator
     */
    function paginate(Wechat $Wechat = null, $classification = 0, $wechat_id = 0, $take = 16, $simple = true)
    {
        if ($wechat_id) {
            if ($Wechat && $wechat_id == $Wechat->id) {
                $video = \App\Models\Video::query()->withoutGlobalScopes()->where('wechat_id', $wechat_id);
            } else {
                $video = \App\Models\Video::query()
                    ->where('wechat_id', $wechat_id);
                if ($Wechat) {
                    if ($Wechat->haveFollowedWechatId($wechat_id)) {
                        $video->whereIn('visibility', [1, 2]);
                    } else {
                        $video->where('visibility', 1);
                    }
                } else {
                    $video->where('visibility', 1);
                }
            }
        } else {
            $video = \App\Models\Video::query()
                ->where(function (Builder $builder) use ($Wechat) {
                    $builder->where('visibility', 1)
                        ->when($Wechat, function (Builder $builder) use ($Wechat) {
                            $builder->orWhere(function (Builder $builder) use ($Wechat) {
                                $followed = $Wechat->followed()
                                    ->pluck('wechat_id')
                                    ->toArray();
                                $builder->whereIn("wechat_id", $followed)->where('visibility', 2);
                            })->orWhere(function (Builder $builder) use ($Wechat) {
                                $builder->where(
                                    "wechat_id",
                                    $Wechat->id
                                )->where('visibility', 3);
                            });
                        });
                });
        }
        $video->when($classification, function (Builder $queries) use ($classification) {
            return $queries->where('classification_id', $classification);
        })
            ->with('wechat')
            ->orderBy('id', 'desc');
        if ($simple) {
            $Paginate = $video->simplePaginate($take);
        } else {
            $Paginate = $video->paginate($take);
        }
        foreach ($Paginate as $item) {
            if (!empty($item->wechat->toArray())) {
                $item->wechat->setAttribute('followed', $Wechat ? $Wechat->haveFollowed($item->wechat) : false);
            } else {
                $item->wechat->setAttribute('followed', false);
            }
            $item->setAttribute('liked', $Wechat ? $Wechat->haveLiked($item) : false);
        }
        return $Paginate;
    }

    function show(\App\Models\Video $video, Wechat $Wechat = null)
    {
        $rows = (object)$video->toArray();
        $rows->wechat = (object)($wechat = $video->wechat)->toArray();
        if ($Wechat) {
            $rows->wechat->followed = $Wechat->haveFollowed($wechat);
            $rows->liked = $Wechat->haveLiked($video);
        } else {
            $rows->wechat->followed = false;
            $rows->liked = false;
        }
        return $rows;
    }

    /**
     * @param array $data
     * @param Wechat $Wechat
     * @return array
     */
    function store(Array $data, Wechat $Wechat)
    {
        if (strtolower(pathinfo($data['url'])['extension']) != 'mp4') {
            $data['status'] = 0;
            $vod = new Vod();
            $task = $vod->convertVodFile($data['file_id']);
            Task::query()->create(
                [
                    'file_id' => $data['file_id'],
                    'code' => $task->code,
                    'code_desc' => $task->codeDesc,
                    'message' => $task->message
                ]
            );
        } else {
            $data['status'] = 1;
            if (!$data['cover_url']) {
                $vod = new Vod();
                Log::warning("用户未上传封面");
                $data['cover_url'] = "https://{$_SERVER["SERVER_NAME"]}/images/video_default_cover.png";
                $task = $vod->createSnapshotByTimeOffsetAsCover($data['file_id'], 1);
                Task::query()->create(
                    [
                        'file_id' => $data['file_id'],
                        'code' => $task->code,
                        'code_desc' => $task->codeDesc,
                        'message' => $task->message
                    ]
                );
            }

        }
        $video = $Wechat->video()->create($data);
        return $video->toArray();
    }
}