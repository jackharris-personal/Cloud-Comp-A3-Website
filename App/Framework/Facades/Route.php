<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - forum
 * Last Updated - 9/09/2023
 */

namespace App\Framework\Facades;

use App\Framework\Application;
use App\Framework\Http\HttpRouter;

class Route
{

    public static function get(string $uri, string $method, string $controller): void
    {
        Application::getInstance()->getHttpRouter()->RegisterRoute($uri,HttpRouter::$ROUTE_GET,$method,$controller);
    }

    public static function post(string $uri, string $method, string $controller): void
    {

        Application::getInstance()->getHttpRouter()->RegisterRoute($uri,HttpRouter::$ROUTE_POST,$method,$controller);
    }

    public static function patch(string $uri, string $method, string $controller): void
    {
        Application::getInstance()->getHttpRouter()->RegisterRoute($uri,HttpRouter::$ROUTE_PATCH,$method,$controller);
    }

    public static function delete(string $uri, string $method, string $controller): void
    {
        Application::getInstance()->getHttpRouter()->RegisterRoute($uri,HttpRouter::$ROUTE_DELETE,$method,$controller);
    }

}