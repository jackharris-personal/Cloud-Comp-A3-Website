<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 14/11/2023
 */

namespace App\Controllers;


use App\Framework\Http\View;

class AuthController
{

    public function login(): View
    {
        return View::load("login.php");
    }


    public function register(): View
    {
        return View::load("register.php");

    }


}