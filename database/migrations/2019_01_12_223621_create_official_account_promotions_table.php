<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialAccountPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_account_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('original_id');
            $table->string('poster');
            $table->string('keywords');
            $table->string('tip');
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
        Schema::dropIfExists('official_account_promotions');
    }
}
