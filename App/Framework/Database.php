<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - nextstats-web
 * Last Updated - 30/10/2023
 */

namespace App\Framework;

use App\Framework\Facades\App;
use http\Env;
use mysqli;
use mysqli_result;

class Database
{

    public static function query(string $query): mysqli_result|bool
    {
        return Database::getConnection()->query($query);
    }

    public static function fetch(string $query): array
    {
        return Database::getConnection()->query($query)->fetch_assoc();
    }

    private static function getConnection(): mysqli
    {

        $username = App::Env("DB_USERNAME");
        $password = App::Env("DB_PASSWORD");
        $host = App::Env("DB_HOST");
        $port = App::Env("DB_PORT");
        $database = App::Env("DB_DATABASE");

        return new mysqli($host,$username,$password,$database,$port);
    }

}