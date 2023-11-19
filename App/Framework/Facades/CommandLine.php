<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 8/11/2023
 */

namespace App\Framework\Facades;

use App\Framework\Application;
use App\Framework\CommandLine\CliRouter;

class CommandLine
{

    public static function register(string $command, string $method, $class): void
    {

        Application::getInstance()->getConsoleRouter()->registerRoute($command,CliRouter::$ROUTE_CLI,$method,$class);

    }

}