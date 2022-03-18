<?php

namespace App\Utils;

use App\Volunteer;

class Helper
{

    public static function user()
    {
        return request()->user();
    }

    public static function checkIsManager()
    {
        $user_id = self::user()->id;

        $gym = Volunteer::where([
            ['user_id', '=', $user_id]
        ])->get();


        return (count($gym) > 0);
    }

    public static function checkIsManagerAndGetManager()
    {
        $user_id = self::user()->id;

        $gym = Volunteer::where([
            ['user_id', '=', $user_id]
        ])->get();

        if (count($gym) > 0) {
            return $gym[0];
        }

        return null;
    }

    public static function stopPrint($obj = '', $msg = '')
    {
        echo $msg ? "$msg<br/>" : "";
        var_dump($obj);
        die();
    }
}
