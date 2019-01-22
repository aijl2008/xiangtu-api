<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/12
 * Time: 下午11:54
 */

namespace App\Service\Wechat\OfficialAccount;


use App\Models\Wechat\OfficialAccount\Event;

class LogEvent
{

    static function log(array $data)
    {
        $event = new Event();
        foreach ($event->getFillable() as $field) {
            if (array_key_exists($field, $data)) {
                $event->$field = (string)$data[$field];
            } else {
                $event->$field = "";
            }
        }
        $event->save();
    }
}