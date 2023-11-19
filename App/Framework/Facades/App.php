<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - nextstats-auth
 * Last Updated - 7/11/2023
 */

namespace App\Framework\Facades;


use App\Framework\Application;

class App
{

    public static function Env(string $key, $default = null)
    {

        $env = Application::getInstance()->getEnv();

        if(array_key_exists($key,$env)){
            return trim($env[$key]);
        }else{
            return $default;
        }

    }

}