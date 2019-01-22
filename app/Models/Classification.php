<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{

    protected $fillable = [
        'name', 'icon', 'status '
    ];

    function getStatusOption()
    {
        return [
            1 => '正常',
            0 => '不可用',
        ];
    }

    function toOption()
    {
        $classifications = array();
        foreach (parent::newQuery()->get() as $item) {
            $classifications[$item->id] = $item->name;
        }
        return $classifications;
    }
}
