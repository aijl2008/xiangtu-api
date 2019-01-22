<?php

namespace App\Console\Commands\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class Register extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '注册管理员用户';

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
        $email = $this->ask('请输入邮箱');
        $name = $this->ask('请输入姓名');
        $password = 'Abcd123@';
        event(new Registered($user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ])));

        $this->comment("注册成功，登录邮箱:" . $email . ",初始密码：" . $password);
    }
}
