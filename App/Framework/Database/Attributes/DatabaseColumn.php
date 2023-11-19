<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 7/11/2023
 */

namespace app\Framework\Database\Attributes;

use Attribute;

#[Attribute]
class DatabaseColumn
{

    private array $column;

    public function __construct(array $properties)
    {
        //Define our column defaults
        $this->column = [
            "NAME" => null,
            "ALLOW_NULL" => true,
            "LENGTH" => 255,
            "AUTO_INCREMENT" => false,
            "TYPE" => null,
            "PRIMARY_KEY" => false
        ];


        //Loop over all our properties and translate them into our column
        foreach (array_keys($this->column) as $item){
            if(array_key_exists($item,$properties)){
                $this->column[$item] = $properties[$item];
            }
        }

    }

    public function setName(string $name): void
    {
        $this->column["NAME"] = $name;
    }

    public function getColumn(): array
    {
        return $this->column;
    }


}