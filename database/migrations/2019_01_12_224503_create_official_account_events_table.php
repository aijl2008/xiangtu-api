<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialAccountEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_account_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string("ToUserName");
            $table->string("FromUserName");
            $table->integer("CreateTime");
            $table->string("MsgType");
            $table->string("Event");//subscribe(订阅)、unsubscribe(取消订阅),SCAN
            $table->string('EventKey');//事件KEY值，qrscene_为前缀，后面为二维码的参数值
            $table->string('Ticket');//二维码的ticket，可用来换取二维码图片
            $table->string("Content");
            $table->string("MsgId");
            $table->string("MediaId");
            $table->string("PicUrl");
            $table->string("Format");
            $table->string("Recognition");
            $table->string("ThumbMediaId");
            $table->string("Latitude");
            $table->string("Longitude");
            $table->string("Precision");
            $table->string("Location_X");
            $table->string("Location_Y");
            $table->string("Scale");
            $table->string("Label");
            $table->string("Title");
            $table->string("Description");
            $table->string("Url");
            $table->string("FileKey");
            $table->string("FileMd5");
            $table->string("FileTotalLen");
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
        Schema::dropIfExists('official_account_events');
    }
}
