<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - nextstats-web
 * Last Updated - 30/10/2023
 */

namespace App\Models;

use App\Framework\Database;
use app\Framework\Database\Attributes\DatabaseColumn;
use http\Header;

class Session
{

    #[DatabaseColumn([
        "PRIMARY_KEY"=>true,
        "TYPE"=>SQL_TYPE_VARCHAR,
        "ALLOW_NULL"=>false
    ])]
    private string $token;

    #[DatabaseColumn([
        "TYPE"=>SQL_TYPE_INT
    ])]
    private int $user_id;

    #[DatabaseColumn([
        "TYPE"=>SQL_TYPE_INT
    ])]
    private int $expiry;

    #[DatabaseColumn([
        "TYPE"=>SQL_TYPE_VARCHAR
    ])]
    private string $driver;

    public function __construct(Array $entity)
    {
        $this->token = $entity["token"];
        $this->expiry = $entity["expiry"];
        $this->user_id = $entity["user_id"];
        $this->driver = $entity["driver"];
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public static function isValid(): bool
    {
        if(self::validateCurrentOrRedirect(false) !== null){
            return true;
        }else{
            return false;
        }
    }

    public static function validateCurrentOrRedirect($redirect = true): ?Session{
        $token= null;
        if(isset($_COOKIE["session"])){
            $token = $_COOKIE["session"];
        }

        $session = Session::lookup($token);

        if($session === null){
            if($redirect) {
                header("Location: /login?alert=warning&message=Please login to access that page!");
                die;
            }
        }

        return $session;
    }

    public static function lookup(?string $token): ?Session
    {
        if($token === null){
            return null;
        }

        $query = "SELECT * FROM Session WHERE token='$token'";
        $result = Database::query($query);

        if($result->num_rows > 0){
            return new Session($result->fetch_assoc());
        }else{
            return null;
        }
    }


}