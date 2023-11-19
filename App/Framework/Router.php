<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 8/11/2023
 */

namespace App\Framework;

abstract class Router
{


    public static string $ROUTE_POST = "POST";
    public static string $ROUTE_GET = "GET";
    public static string $ROUTE_PATCH = "PATCH";
    public static string $ROUTE_DELETE = "DELETE";
    public static string $ROUTE_CLI = "CLI";

    protected array $routes;

    protected $prefix;


    abstract function registerRoute(string $uri, string $type, string $method, $controller);

    abstract function loadRoutes(string $file,string $prefix = null);

    protected function CheckForVariable(string $section): bool
    {
        $outcome = false;

        if(str_starts_with($section, '{') && str_ends_with($section, '}')){

            $outcome = true;

        }

        return $outcome;
    }

    public function GetUriAsArray(string $url = null, string $separator = "/"): array
    {
        if($url === null){
            $url = $_SERVER["REQUEST_URI"];
        }

        if(strpos($url,"?")){
            $url = substr($url, 0, strrpos($url,"?"));
        }

        return array_filter(explode($separator, $url));
    }

    public function analyseRouteAndLookup(array $route): array
    {

        $uri = $route;
        $routes = $this->routes[$_SERVER["REQUEST_METHOD"]];
        $separator = "/";

        if($_SERVER["REQUEST_METHOD"] === Router::$ROUTE_CLI){
            $separator = " ";
            array_shift($uri);
        }

        $response = [
            "route"=>null,
            "outcome"=>false
        ];

        foreach ($routes as $key => $route){

            $routeKey = $this->GetUriAsArray($key,$separator);
            $variables = [];
            $checks = [1=>false];

            if(count($routeKey) === count($uri)){

                if($_SERVER["REQUEST_METHOD"] === Router::$ROUTE_CLI){
                    $count  = 0;
                }else{
                    $count  = 1;
                }

                foreach ($routeKey as $section){

                    if(!$this->CheckForVariable($section)){
                        if($uri[$count] === $section){
                            $checks[$count] = true;
                        }else{
                            $checks[$count] = false;
                        }
                    }else{
                        $variables[ltrim(rtrim($section,'}'),'{')] = $uri[$count];
                    }
                    $count++;
                }
            }

            if(!in_array(false,$checks,true)){

                $response["route"] = $key;
                $response["outcome"] = true;
                $response["controller"] = $route["controller"];
                $response["method"] =  $route["method"];
                $response["variables"] = $variables;
                break;
            }
        }

        return $response;
    }

}