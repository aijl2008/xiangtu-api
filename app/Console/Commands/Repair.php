<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Repair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repair';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修复冗余数据';

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
        $sqls = [
            "drop table if exists tmp",
            "create temporary table tmp SELECT wechat_id, COUNT(*) as uploaded_number FROM videos GROUP BY wechat_id",
            "UPDATE wechats, tmp SET wechats.uploaded_number = tmp.uploaded_number WHERE wechats.id = tmp.wechat_id",

            "drop table if exists tmp",
            "create temporary table tmp SELECT to_user_id, COUNT(*) as played_number FROM logs WHERE action = '播放' GROUP BY to_user_id",
            "UPDATE wechats, tmp SET wechats.played_number = tmp.played_number WHERE wechats.id = tmp.to_user_id",

            "drop table if exists tmp",
            "create temporary table tmp SELECT video_id, COUNT(*) as played_number FROM logs WHERE action = '播放' GROUP BY video_id",
            "UPDATE videos, tmp SET videos.played_number = tmp.played_number WHERE videos.id = tmp.video_id",

            "drop table if exists tmp",
            "create temporary table tmp SELECT followed_id, COUNT(*) as followed_number FROM followed_wechat GROUP BY followed_id",
            "UPDATE wechats, tmp SET wechats.followed_number = tmp.followed_number WHERE wechats.id = tmp.followed_id",

            "drop table if exists tmp",
            "create temporary table tmp SELECT wechat_id, COUNT(*) as be_followed_number FROM followed_wechat GROUP BY wechat_id",
            "UPDATE wechats, tmp  SET wechats.be_followed_number = tmp.be_followed_number WHERE wechats.id = tmp.wechat_id",

            "drop table if exists tmp",
            "create temporary table tmp as SELECT video_id,count(*) as liked_number FROM yrl.video_wechat group by video_id",
            "UPDATE videos, tmp SET videos.liked_number = tmp.liked_number WHERE videos.id = tmp.video_id",

            "drop table if exists tmp",
            "create temporary table tmp SELECT video_id, COUNT(*) as shared_wechat_number FROM logs WHERE action = '分享到聊天' GROUP BY video_id",
            "UPDATE videos, tmp SET videos.shared_wechat_number = tmp.shared_wechat_number WHERE videos.id = tmp.video_id",

            "drop table if exists tmp",
            "create temporary table tmp SELECT video_id, COUNT(*) as shared_moment_number FROM logs WHERE action = '分享到朋友圈' GROUP BY video_id",
            "UPDATE videos, tmp SET videos.shared_moment_number = tmp.shared_moment_number WHERE videos.id = tmp.video_id",
        ];
        foreach ($sqls as $sql) {
            DB::update($sql);
        }
    }
}
