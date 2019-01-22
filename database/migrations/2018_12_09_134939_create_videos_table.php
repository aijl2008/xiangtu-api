<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->default('');
            $table->string('url', 255)->default('');
            $table->string('cover_url', 255)->default('');
            $table->string('file_id')->default('');
            $table->dateTime('uploaded_at');
            $table->string('wechat_id')->default('');
            $table->integer('classification_id')->default(0);
            $table->integer('visibility')->default(1);
            $table->integer('played_number')->default(0);
            $table->integer('liked_number')->default(0);
            $table->integer('shared_wechat_number')->default(0);
            $table->integer('shared_moment_number')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('size')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('videos');
    }
}