<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager
 * Last Updated - 19/11/2023
 */

namespace App\Views;

class Alert
{

    public static function render(): void
    {

        $type = null;
        $message = null;

        if(isset($_GET["alert"])){
            $type = $_GET["alert"];
        }

        if(isset($_GET["message"])){
            $message = $_GET["message"];
        }

        if($message !== null && $type !== null){
            echo '<div class="alert alert-'.$type.'" role="alert">
             '.$message.'
            </div>';
        }

    }

}