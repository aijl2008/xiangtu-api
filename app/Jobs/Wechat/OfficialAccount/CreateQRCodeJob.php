<?php

namespace App\Jobs\Wechat\OfficialAccount;

use App\Models\Wechat\OfficialAccount\Follower;
use App\Models\Wechat\OfficialAccount\OfficialAccount as Model;
use App\Service\Wechat\OfficialAccount\OfficialAccount;
use App\Service\Wechat\OfficialAccount\QRCode;
use EasyWeChat\Kernel\Messages\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CreateQRCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model = null;
    protected $follower = null;

    /**
     * CreateQRCodeJob constructor.
     * @param Model $model
     * @param Follower $follower
     */
    public function __construct(Model $model, Follower $follower)
    {
        $this->model = $model;
        $this->follower = $follower;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->model->app_id . '-' . $this->follower->open_id . ".jpeg";
        $app = OfficialAccount::instance($this->model->app_id);
//        if (!Storage::exists($file)) {
            $canvas = QRCode::make($this->model, $this->follower);
            Storage::put($file, $canvas->encode());
//        }
        $uploaded = $app->media->uploadImage(Storage::path($file));
        $app->customer_service->message(new Image($uploaded['media_id']))->to($this->follower->open_id)->send();
    }
}
