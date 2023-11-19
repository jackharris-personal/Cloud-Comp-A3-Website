<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - forum
 * Last Updated - 29/09/2023
 */

namespace App\Models;

use App\Framework\Database;
use App\Framework\Database\Attributes\DatabaseColumn;
use App\Framework\Facades\App;


class User
{

    #[DatabaseColumn([
        "PRIMARY_KEY"=>true,
        "TYPE"=>SQL_TYPE_INT,
        "ALLOW_NULL"=>false,
        "AUTO_INCREMENT"=>true
    ])]
    private int $id;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $mail;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $givenName;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $surname;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $displayName;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private ?string $jobTitle;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private ?string $company;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $password;

    #[DatabaseColumn(["TYPE"=>SQL_TYPE_VARCHAR])]
    private string $photo;

    private array $entity;

    public function __construct(Array $entity){

        $this->id = $entity["id"];
        $this->mail = $entity["mail"];
        $this->givenName = $entity["givenName"];
        $this->surname = $entity["surname"];
        $this->displayName = $entity["displayName"];
        $this->password = $entity["password"];
        $this->jobTitle = $entity["jobTitle"];
        $this->company =  $entity["company"];
        $this->photo  =$entity["photo"];

        $this->entity = $entity;

        unset($this->entity["password"]);
    }

    public function GetId(): int
    {
        return $this->id;
    }

    public function GetGivenName(): string
    {
        return $this->givenName;
    }

    public function GetSurname(): string{
        return $this->surname;
    }

    public function GetMail(): string
    {
        return $this->mail;
    }


    public function GetPassword(): string
    {
        return $this->password;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function getEntity(): array
    {
        return $this->entity;
    }

    public function getPhoto(): string{
        return $this->photo;
    }

    public static function LookUpByEmail(string $email): ?User
    {

        $query = "SELECT * FROM User WHERE mail='$email'";

        $result = Database::query($query);

        if($result->num_rows > 0){
            return new User($result->fetch_assoc());
        }else{
            return null;
        }

    }

    public static function lookUp(int $id): ?User
    {
        $query = "SELECT * FROM User WHERE id='$id'";
        $result = Database::query($query);

        if($result->num_rows > 0){
            return new User($result->fetch_assoc());
        }else{
            return null;
        }
    }

    public static function register(string $mail, string $givenName, string $surname, string $password): bool
    {
        $displayName = ucfirst($givenName)." ".ucfirst($surname);
        $photo = App::Env("AWS_CLOUD_FRONT")."/user.png";

        $query = "INSERT INTO User (mail, givenName, surname, displayName ,password, photo) VALUES ('$mail','$givenName','$surname','$displayName','$password','$photo')";

        if(Database::query($query)){
            return true;
        }else{
            return false;
        }

    }


}