<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialAccountFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_account_followers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original_id')->default("");
            $table->string('from_open_id')->default("");
            $table->string('open_id')->default("");
            $table->string('nickname')->default("");
            $table->string('sex')->default("");
            $table->string('language')->default("");
            $table->string('city')->default("");
            $table->string('province')->default("");
            $table->string('country')->default("");
            $table->string('headimgurl')->default("");
            $table->string('subscribe_time')->default("");
            $table->string('unionid')->default("");
            $table->string('remark')->default("");
            $table->integer('groupid')->default(0);
            $table->string('tagid_list')->default("");
            $table->string('subscribe_scene')->default("");
            $table->string('qr_scene')->default("");
            $table->string('qr_scene_str')->default("");
            $table->dateTime('followed_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_account_followers');
    }
}
