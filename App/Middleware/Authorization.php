<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 14/11/2023
 */

namespace App\Middleware;


use App\Models\Session;

class Authorization
{

    private static ?Session $current;

    public static function check(): bool
    {
        $headers = getallheaders();
        if(!isset($headers["Authorization"])){
            return false;
        }

        $value = $headers["Authorization"];
        if(!str_contains($value,"Bearer ")){
           return false;
        }

        $token = substr($value,7,strlen($value)-7);

        Authorization::$current = Session::lookup($token);

        if(Authorization::$current === null){
            return false;
        }

        return true;
    }

    public static function getCurrentSession(): Session
    {
        return Authorization::$current;
    }

}