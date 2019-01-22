<?php

namespace App\Jobs\Wechat\OfficialAccount;

use App\Models\Wechat\OfficialAccount\Follower;
use App\Service\Wechat\OfficialAccount\OfficialAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $original_id = null;
    protected $follower = null;

    public function __construct($original_id, Follower $follower)
    {
        $this->original_id = $original_id;
        $this->follower = $follower;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $app = OfficialAccount::instance($this->original_id);
        $user = $app->user->get($this->follower->open_id);
        $this->follower->nickname = $user["nickname"] ?? "";
        $this->follower->sex = $user["sex"] ?? 0;
        $this->follower->language = $user["language"] ?? "";
        $this->follower->city = $user["city"] ?? "";
        $this->follower->province = $user["province"] ?? "";
        $this->follower->country = $user["country"] ?? "";
        $this->follower->headimgurl = $user["headimgurl"] ?? "";
        $this->follower->subscribe_time = $user["subscribe_time"] ?? 0;
        $this->follower->unionid = $user["unionid"] ?? "";
        $this->follower->remark = $user["remark"] ?? "";
        $this->follower->tagid_list = json_encode($user["tagid_list"] ?? []);
        $this->follower->subscribe_scene = $user["subscribe_scene"] ?? "";
        $this->follower->qr_scene = $user["qr_scene"] ?? "";
        $this->follower->qr_scene_str = $user["qr_scene_str"] ?? "";
        $this->follower->save();

    }
}
