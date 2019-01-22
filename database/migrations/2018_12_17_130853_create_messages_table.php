<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to_user_name')->default('');
            $table->string('from_user_name')->default('');
            $table->string('create_time')->default('');
            $table->string('msg_type')->default('');
            $table->string('content')->default('');
            $table->string('pic_url')->default('');
            $table->string('media_id')->default('');
            $table->string('title')->default('');
            $table->string('app_id')->default('');
            $table->string('page_path')->default('');
            $table->string('thumb_url')->default('');
            $table->string('thumb_media_id')->default('');
            $table->string('event')->default('');
            $table->string('session_from')->default('');
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
        Schema::dropIfExists('messages');
    }
}