<?php

namespace App\Console\Commands;

use App\Models\Classification;
use App\Models\FollowedWechat;
use App\Models\FollowerReport;
use App\Models\Log;
use App\Models\Video;
use App\Models\VideoReport;
use App\Models\Wechat;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

class Demo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '填充演示数据';

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
        Wechat::query()->getConnection()->table('followed_wechat')->truncate();
        Wechat::query()->getConnection()->table('video_wechat')->truncate();
        Wechat::query()->truncate();
        Video::query()->truncate();
        VideoReport::query()->truncate();
        FollowerReport::query()->truncate();
        Log::query()->truncate();
        Classification::query()->truncate();
        $this->comment('填充分类');
        foreach ([
                     [
                         'name' => '社会',
                         'icon' => 'fa-newspaper-o'
                     ],
                     [
                         'name' => '搞笑',
                         'icon' => 'fa-cube'
                     ],
                     [
                         'name' => '生活',
                         'icon' => 'fa-glass'
                     ],
                     [
                         'name' => '影视',
                         'icon' => 'fa-film'
                     ],
                     [
                         'name' => '娱乐',
                         'icon' => 'fa-coffee'
                     ],
                     [
                         'name' => '音乐',
                         'icon' => 'fa-music'
                     ],
                     [
                         'name' => '舞蹈',
                         'icon' => 'fa-female'
                     ],
                     [
                         'name' => '游戏',
                         'icon' => 'fa-gamepad'
                     ]
                     ,
                     [
                         'name' => '美食',
                         'icon' => 'fa-lemon-o'
                     ]
                     ,
                     [
                         'name' => '旅行',
                         'icon' => 'fa-plane'
                     ]
                     ,
                     [
                         'name' => '时尚',
                         'icon' => 'fa-industry'
                     ]
                     ,
                     [
                         'name' => '科技',
                         'icon' => 'fa-desktop'
                     ]
                     ,
                     [
                         'name' => '运动',
                         'icon' => 'fa-futbol-o'
                     ]
                 ] as $item) {
            Classification::query()->create(
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'status' => 1
                ]
            );
        }
        $this->comment('Ok');
        $faker = Faker::create('zh_CN');

        $mockUsersAvatars = [];
        for ($i = 0; $i < 198; $i++) {
            array_unshift($mockUsersAvatars, env('APP_URL') . '/avatar/' . $i . '.jpg');
        }
        for ($i = 0; $i < 198; $i++) {
            array_unshift($mockUsersAvatars, env('APP_URL') . '/avatar/' . $i . '.jpg');
        }
        for ($i = 0; $i < 198; $i++) {
            array_unshift($mockUsersAvatars, env('APP_URL') . '/avatar/' . $i . '.jpg');
        }
        $mockVideoCovers = [];
        for ($i = 4; $i < 700; $i++) {
            array_unshift($mockVideoCovers, env('APP_URL') . '/cover/' . $i . '.png');
        }
        for ($i = 4; $i < 700; $i++) {
            array_unshift($mockVideoCovers, env('APP_URL') . '/cover/' . $i . '.png');
        }
        for ($i = 4; $i < 700; $i++) {
            array_unshift($mockVideoCovers, env('APP_URL') . '/cover/' . $i . '.png');
        }
        $mockVideos = json_decode(file_get_contents(base_path() . '/database/mock/videos.json'));
        for ($i = 0; $i < 100; $i++) {
            $this->comment('填充用户');
            $wechat = new Wechat(
                [
                    "open_id" => $faker->regexify('[0-9A-Z]{32}'),//18+1+42
                    "union_id" => null,
                    "avatar" => array_pop($mockUsersAvatars),
                    "nickname" => $faker->name,
                    "sex" => $faker->numberBetween(0, 2),
                    "country" => $faker->country,
                    "province" => $faker->state,
                    "city" => $faker->phoneNumber,
                    "status" => 1
                ]
            );
            $wechat->save();
            $this->comment($wechat->nickname . ',Ok');
            $this->comment('填充视频');
            for ($n = 0; $n < mt_rand(5, 10); $n++) {
                $mock = array_pop($mockVideos);
                $video = new Video(
                    [
                        "title" => $mock->title,
                        "url" => $mock->url,
                        "cover_url" => $mock->cover_url,
                        "file_id" => '5285890783657415584',//京东
                        "uploaded_at" => $faker->dateTime,
                        "classification_id" => $faker->numberBetween(1, 13),
                        "played_number" => 0,
                        "liked_number" => 0,
                        "shared_wechat_number" => 0,
                        "shared_moment_number" => 0,
                        "visibility" => $faker->numberBetween(1, 3),
                        "status" => 1
                    ]
                );
                $wechat->video()->save($video);
                $this->comment($video->title . ',Ok');
            }
        }

        $this->comment('填充播放');
        for ($i = 0; $i < 1000; $i++) {
            Log::query()->create(
                [
                    'action' => '播放',
                    'from_user_id' => mt_rand(0, 100),
                    'to_user_id' => 0,
                    'video_id' => mt_rand(0, 100),
                    'message' => '',
                    'created_at' => date('Y-m-d H:i:s', mt_rand(time() - 3600 * 24 * 7, time())),
                    'updated_at' => date('Y-m-d H:i:s', mt_rand(time() - 3600 * 24 * 7, time()))
                ]
            );
        }

        foreach (Wechat::query()->get() as $wechat) {
            $this->comment('添加收藏');
            $many = $many = Wechat::query()->inRandomOrder()->take(mt_rand(5, 10))->get();
            foreach ($many as $item) {
                $wechat->followed()->save(new FollowedWechat([
                    'wechat_id' => $item->id,
                    'followed_id' => $wechat->id
                ]));
                Log::query()->create(
                    [
                        'action' => '关注',
                        'from_user_id' => $wechat->id,
                        'to_user_id' => $item->id,
                        'video_id' => 0,
                        'message' => $wechat->nickname . '关注了' . $item->nickname,
                        'created_at' => date('Y-m-d H:i:s', mt_rand(time() - 3600 * 24 * 7, time())),
                        'updated_at' => date('Y-m-d H:i:s', mt_rand(time() - 3600 * 24 * 7, time())),
                    ]
                );
            }
            $this->comment('Ok');
            $this->comment('添加关注');
            $wechat->liked()->saveMany($many = Video::query()->inRandomOrder()->take(mt_rand(5, 10))->get());
            foreach ($many as $item) {
                Log::query()->create(
                    ['action' => '收藏',
                        'from_user_id' => $wechat->id,
                        'to_user_id' => 0,
                        'video_id' => $item->id,
                        'message' => $wechat->nickname . '收藏了一个视频',
                        'created_at' => date('Y-m-d H:i:s', mt_rand(time() - 3600 * 24 * 7, time())),
                        'updated_at' => date('Y-m-d H:i:s', mt_rand(time() - 3600 * 24 * 7, time()))
                    ]
                );
            }


            $this->comment('Ok');
        }

        $this->comment('Ok');
    }
}