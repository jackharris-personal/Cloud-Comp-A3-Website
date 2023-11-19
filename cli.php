<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 8/11/2023
 */


use App\Framework\Application;

//Define our root file
define("ROOT", getcwd() . DIRECTORY_SEPARATOR);

$_SERVER["REQUEST_METHOD"] = "CLI";

//Include our app boostrap autoloader
require_once ROOT . "App" . DIRECTORY_SEPARATOR . "Framework" . DIRECTORY_SEPARATOR . "autoloader.php";

//Next we get our application instance
$app = Application::getInstance();

$app->loadRoutes("cli.php");

$app->executeConsoleCommand();