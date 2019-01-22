<?php

namespace App\Console\Commands;

use App\Models\FollowerReport;
use App\Models\Log;
use App\Models\Video;
use App\Models\VideoReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成统计日报';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->argument('date');
        if (!$date) {
            $date = date('Y-m-d');
        }
        VideoReport::query()->where('date', $date)->delete();
        $items = Log::query()->where('action', '播放')
            ->whereBetween('updated_at', [
                $date . ' 00:00:00',
                $date . ' 23:59:59'
            ])
            ->groupBy('video_id')
            ->select(DB::raw('video_id, count(id) as aggregate'))
            ->get();
        foreach ($items as $item) {
            $this->comment('[' . __LINE__ . ']processing ' . $item->video_id);
            $ReportVideo = new VideoReport();
            $ReportVideo->date = $date;
            $video = Video::query()->find($item->video_id);
            $ReportVideo->wechat_id = $video->wechat->id ?? 0;
            $ReportVideo->video_id = $item->video_id;
            $ReportVideo->played_number = $item->aggregate;
            $ReportVideo->save();
        }
        FollowerReport::query()->where('date', $date)->delete();
        $items = Log::query()->where('action', '关注')
            ->whereBetween('updated_at', [
                $date . ' 00:00:00',
                $date . ' 23:59:59'
            ])
            ->groupBy('from_user_id')
            ->select(DB::raw(
                'from_user_id, 
                count(id) as followed_number'
            ))
            ->get();
        foreach ($items as $item) {
            $this->comment('[' . __LINE__ . ']processing ' . $item->from_user_id);
            $ReportVideo = new FollowerReport();
            $ReportVideo->date = $date;
            $ReportVideo->wechat_id = $item->from_user_id;
            $ReportVideo->followed_number = $item->followed_number;
            $ReportVideo->cancel_followed_number = 0;
            $ReportVideo->save();
        }
        $items = Log::query()->where('action', '取消关注')
            ->whereBetween('updated_at', [
                $date . ' 00:00:00',
                $date . ' 23:59:59'
            ])
            ->groupBy('from_user_id')
            ->select(DB::raw(
                'from_user_id, 
                count(id) as cancel_followed_number'
            ))
            ->get();
        foreach ($items as $item) {
            $this->comment('[' . __LINE__ . ']processing ' . $item->from_user_id);
            $ReportVideo = FollowerReport::query()
                ->where('date', $date)
                ->where('wechat_id', $item->from_user_id)
                ->firstOrNew();
            $ReportVideo->date = $date;
            $ReportVideo->wechat_id = $item->from_user_id;
            if (!$ReportVideo->followed_number) {
                $ReportVideo->followed_number = 0;
            }
            $ReportVideo->cancel_followed_number = $item->cancel_followed_number;
            $ReportVideo->save();
        }
        $this->comment('Ok');
    }
}
