<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWechatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('open_id')->nullable();
            $table->string('union_id')->nullable();
            $table->string('avatar', 255);
            $table->string('nickname')->default('');
            $table->tinyInteger('sex')->default(0);
            $table->string('country')->default('');
            $table->string('province')->default('');
            $table->string('city')->default('');
            $table->string('mobile')->default('');
            $table->tinyInteger('status')->default(0);
            $table->integer('followed_number')->default(0);
            $table->integer('be_followed_number')->default(0);
            $table->integer('uploaded_number')->default(0);
            $table->integer('played_number')->default(0);
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
        Schema::dropIfExists('wechats');
    }
}