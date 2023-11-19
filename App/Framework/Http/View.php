<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager
 * Last Updated - 18/11/2023
 */

namespace App\Framework\Http;

use App\Framework\Facades\App;

class View
{
    private static array $bag = [];

    private string $path;
    private int $statusCode;

    private static string $layout = "layout";

    public function __construct(string $path, int $statusCode = 200){

        $this->path = $path;
        $this->statusCode = $statusCode;
        View::Bag()["title"] = App::Env("APP_NAME","App name not set!");

    }

    public static function load(string $path, int $statusCode = 200): View
    {

        return new View($path,$statusCode);

    }

    public static function setLayout(string $folder): void
    {
        self::$layout = $folder;
    }

    public function execute(): void
    {
        if(file_exists(VIEWS.$this->path)){

            require_once VIEWS.self::$layout.DIRECTORY_SEPARATOR."header.php";
            require_once VIEWS.$this->path;
            require_once VIEWS.self::$layout.DIRECTORY_SEPARATOR."footer.php";

        }else{
            $view = View::load("error".DIRECTORY_SEPARATOR."500.php",500);
            View::Bag()["error"] = $this->path." not found in views folder.";
            $view->execute();
            die;
        }
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    public static function &Bag(): array
    {
        return self::$bag;
    }


}