<?php

namespace App\Models\Wechat\OfficialAccount;

use Illuminate\Database\Eloquent\Model;

class OfficialAccount extends Model
{
    protected $table = "official_accounts";

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'original_id', 'original_id');
    }
}
