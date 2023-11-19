<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 7/11/2023
 */

namespace app\Framework\Database;

use App\Framework\Database;
use app\Framework\Database\Attributes\DatabaseColumn;
use ReflectionClass;

class Migration
{


    private array $types;
    private string $primaryKey;

    public function __construct()
    {
        $this->types[4] = "int";
        $this->types[12] = "varchar";
        $this->types[10] = "text";
    }

    public function make($class): bool
    {

        $reflection = new ReflectionClass($class);

        $name = $this->getClassShortName($reflection);

        $query = "CREATE TABLE `".$name."` (";

        foreach ($reflection->getProperties( )as $property){

            $attributes =  $property->getAttributes(DatabaseColumn::class);

            foreach ($attributes as $attribute){
                $instance = $attribute->newInstance();
                $instance->setName($property->name);

                $query .= $this->buildColumnString($instance->getColumn());
            }
        }

        $query .= "PRIMARY KEY (`".$this->primaryKey."`)";
        $query .= ");";


        return Database::query($query);
    }

    private function getClassShortName($reflection): string
    {

        return trim($reflection->getName(),$reflection->getNamespaceName());
    }

    private function buildColumnString(array $column): string
    {

        $string = "`".$column["NAME"]."` ".$this->types[$column["TYPE"]]."(".$column["LENGTH"].")";

        if(!$column["ALLOW_NULL"]){
            $string .= " NOT NULL";
        }

        if($column["PRIMARY_KEY"]){
            $this->primaryKey = $column["NAME"];
        }

        if($column["AUTO_INCREMENT"]){
            $string .= " AUTO_INCREMENT";
        }

        return $string.",";
    }

    private function checkIfTableExists(string $table){
        $query = "SHOW TABLES LIKE '".$table."'";
    }

}