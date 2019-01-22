<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOfficialAccountsTable
 * insert into official_accounts value (0,"wxda5f7e86d91086d5","gh_3f28dc8c40ec","1b09e65b71c01f7631efb3a71b7c145e","wechatapiawzcn","wechatapiawzcn",'Jerry的测试号',now(),now());
 */
class CreateOfficialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id')->default("");
            $table->string('original_id')->default("");
            $table->string('secret')->default("");
            $table->string('token')->default("");
            $table->string('aes_key')->default("");
            $table->string('name')->default("");
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
        Schema::dropIfExists('official_accounts');
    }
}
