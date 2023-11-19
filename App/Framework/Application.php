<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - forum
 * Last Updated - 9/09/2023
 */

namespace App\Framework;

use App\Framework\CommandLine\CliRouter;
use App\Framework\Facades\App;
use App\Framework\Http\HttpRouter;
use App\Framework\Http\Response;
use App\Framework\Http\View;
use ReflectionClass;

class Application
{
    private HttpRouter $httpRouter;
    private CliRouter $consoleRouter;
    private static ?Application $instance = null;
    private array $env;

    public function __construct(){

        //Create our router instance
        $this->httpRouter = new HttpRouter();
        $this->consoleRouter = new CliRouter();

        //Check if we have a .env file present, if not
        //then we create the file.
        if(!file_exists(ROOT.".env")){
            $file = fopen(ROOT.".env","w");
            fclose($file);
        }

        //Load the env file
        $this->env = $this->LoadEnv(".env");
    }


    public function boot(){
        date_default_timezone_set(App::Env("TIME_ZONE","Australia/Melbourne"));
    }

    public function getEnv(): array
    {
        return $this->env;
    }

    public static function getInstance(): Application
    {
        if(Application::$instance == null){
            Application::$instance = new Application();
        }

        return Application::$instance;
    }

    public function getHttpRouter(): HttpRouter{
        return $this->httpRouter;
    }

    public function getConsoleRouter() : CliRouter
    {
        return $this->consoleRouter;
    }

    public function loadRoutes(string $route, string $prefix = null): void
    {
        $this->httpRouter->loadRoutes($route,$prefix);
    }


    public function executeHttpRequest(): array
    {
        $this->boot();
        $httpResponse = new Response();

        $response = $this->httpRouter->AnalyseRouteAndLookup($this->httpRouter->GetUriAsArray($_SERVER["REQUEST_URI"]));

        //Validate if we have a valid route.
        if(!$response["outcome"]){
            $view = View::load("error".DIRECTORY_SEPARATOR."404.php");
            $view->execute();
            die;
        }


        $controller = new $response["controller"]();

        //Check if we have a valid method, if not throw a 500 error.
        if(!method_exists($controller,$response["method"])) {

            $view = View::load("error".DIRECTORY_SEPARATOR."500.php",500);
            View::Bag()["error"] = "Method : ".$response["method"].", not found in controller : ".$response["controller"];
            $view->execute();
            die;
        }

        //Next we check if we have any variables to pass, if not we run the method.
        //Next this as we have not returned we have variables to pass
        $reflect = new ReflectionClass($response["controller"]);
        $method = $reflect->getMethod($response["method"]);

        if(count($response["variables"]) === 0){

            $method = $response["method"];
            $result = $controller->$method();
            http_response_code($result->getStatusCode());
            $result->execute();
            die;
        }

        $argCount = count($method->getParameters());
        $varCount = count($response["variables"]);

        //Check our var count matches our parameter count, if not return an error.
        if($argCount !== $varCount){

            $view = View::load("error".DIRECTORY_SEPARATOR."500.php",500);
            View::Bag()["error"] = "Method handler error, argument mismatch, required arguments = ".$varCount.", provided = ".$argCount;
            $view->execute();
            die;
        }

        //else we return outcome and pass the variables.

        $result = $method->invokeArgs($controller,$response["variables"]);
        http_response_code($result->getStatusCode());
        $result->execute();
        die;


    }

    private function LoadEnv(string $file): array
    {
        $file = fopen(ROOT.$file,"r");
        $output = [];

        if($file){
            while(($line = fgets($file)) !== false){
                if(trim($line) !== ""){
                    $input = explode("=",$line);
                    $output[$input[0]] = $input[1];
                }
            }
        }

        fclose($file);

        return $output;
    }

    public function executeConsoleCommand(): void
    {
        $this->boot();

        $response = $this->consoleRouter->AnalyseRouteAndLookup($_SERVER["argv"]);


        if(!$response["outcome"]){
            echo "Invalid command, please try again!";
            die;
        }

        $controller = new $response["controller"]();

        if(!method_exists($controller,$response["method"])) {
            echo "Invalid command execution method provided, method: '".$response["method"]."' does not exist inside ".$controller::class;
            die;
        }

        //Next this as we have not returned we have variables to pass
        $reflect = new ReflectionClass($response["controller"]);
        $method = $reflect->getMethod($response["method"]);

        $argCount = count($method->getParameters());
        $varCount = count($response["variables"]);

        //Check our var count matches our parameter count, if not return a error.
        if($argCount !== $varCount){
            echo "Method handler error, invalid amount of arguments accepted, required = ".$varCount.", provided = ".$argCount;
            die;
        }

        echo $method->invokeArgs($controller,$response["variables"]);
    }
}