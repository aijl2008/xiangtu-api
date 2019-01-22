<?php

namespace App\Models\Wechat\OfficialAccount;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = "official_account_promotions";
    protected $fillable = ["name", "original_id", "poster", "keywords", "tip"];

}
