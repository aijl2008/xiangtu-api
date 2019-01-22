<?php

namespace App\Http\Controllers\Api\Vod;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    function event()
    {
        try {
            $content = file_get_contents("php://input");
            Log::debug($content);
            $decoded = json_decode($content);
            Event::query()->create(
                [
                    'version' => $decoded->version,
                    'type' => $decoded->eventType,
                    'code' => $decoded->data->errCode ?? '',
                    'message' => $decoded->data->message ?? '',
                    'status' => $decoded->data->status ?? '',
                    'data' => print_r($decoded->data, true)
                ]
            );
            switch ($decoded->eventType) {
                case 'CreateSnapshotByTimeOffsetComplete':
                    $url = $decoded->data->picInfo[0]->url ?? null;
                    if ($url) {
                        Video::query()
                            ->where('file_id', $decoded->data->fileId)
                            ->update(
                                [
                                    'cover_url' => $url,
                                    'status' => 1
                                ]
                            );
                    }
                    break;
                case 'NewFileUpload':
                    $video = Video::query()->where('file_id', $decoded->data->fileId)->first();
                    if (!$video) {
                        Log::error("数据异常，找不到file_id为{$decoded->data->fileId}的视频");
                    }
                    $video->wechat->increment('uploaded_number');
                    (new \App\Models\Log())->setRequest(request())->log("上传视频", $video->wechat->id, 0, $video->id);
                    break;

                case 'TranscodeComplete':
                    $video = Video::query()->where('file_id', $decoded->data->fileId)->first();
                    if (!$video) {
                        Log::error("数据异常，找不到file_id为{$decoded->data->fileId}的视频");
                    }
                    $cover_url = $decoded->data->coverUrl ?: null;
                    if ($cover_url) {
                        $video->cover_url = $cover_url;
                        $video->duration = $decoded->data->duration ?? 0;
                        $video->status = 1;
                        $video->save();
                    }
                    break;

                case 'ProcedureStateChanged':
                    break;
                case 'FileDeleted':
                case 'ClipComplete':
                case 'ConcatComplete':
                case 'PullComplete':
                    break;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
        return '{"version":"4.0","eventType":"TestEvent","data":{"event_time":' . time() . ',"data":"xiangtu"}}';
    }
}
