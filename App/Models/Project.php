<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 18/11/2023
 */

namespace App\Models;

use App\Framework\Database;
use app\Framework\Database\Attributes\DatabaseColumn;

class Project
{

    #[DatabaseColumn([
        "PRIMARY_KEY"=>true,
        "TYPE"=>SQL_TYPE_INT,
        "ALLOW_NULL"=>false,
        "AUTO_INCREMENT"=>true
    ])]
    private int $id;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $name;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR,"LENGTH"=>65000])]
    private string $description;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $code;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_INT])]
    private string $user_id;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_INT])]
    private string $created_at;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_INT])]
    private string $last_updated;


    public function getName(){
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getCreatedDate(){
        return $this->created_at;
    }

    public static function getDocuments(){
        $output = [];
        $id = Session::validateCurrentOrRedirect()->getUserId();

        $query = "SELECT * FROM Project WHERE user_id='$id'";
        $result = Database::query($query);

        return $result->fetch_all();
    }

    public static function getDocument($id)
    {
        $query = "SELECT * FROM Project WHERE id='$id'";

        $result = Database::query($query);

        return $result->fetch_assoc();
    }

}