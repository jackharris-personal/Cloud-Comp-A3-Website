<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager-AuthApi
 * Last Updated - 14/11/2023
 */

namespace App\Framework\Http;
use App\Framework\Application;

class Response
{

    private static ?Response $instance = null;

    private bool $outcome = true;

    private string $message = "Request successfully executed.";
    private int $statusCode = 200;

    private array $content = [];
    private array $errors = [];

    public function setOutcome(bool $outcome){
        $this->outcome = $outcome;
    }

    public function setMessage(string $message){
        $this->message = $message;
    }

    public function setStatusCode(int $statusCode){
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int{
        return $this->statusCode;
    }

    public function addContent(string $key, $content): void
    {
        $this->content[$key] = $content;
    }

    public function addError(string $key, $error){
        $this->errors[$key] = $error;
    }

    public function getArray(): array
    {
        $array["outcome"] = $this->outcome;
        $array["status"] = $this->statusCode;
        $array["message"] = $this->message;
        $array["content"] = $this->content;
        $array["errors"] = $this->errors;


        return $array;
    }

    public static function get(): Response
    {
        if(Response::$instance == null){
            Response::$instance = new Response();
        }

        return Response::$instance;
    }

    public static function getUnauthorized(): Response
    {
        $response = new Response();
        $response->setStatusCode(401);
        $response->setOutcome(false);
        $response->setMessage("Unauthorized, please login to access the selected resource.");

        return $response;
    }

    public static function getResourceNotFound(): Response
    {
        $response = new Response();
        $response->setMessage("Error, the request resource was not found.");
        $response->setOutcome(false);
        $response->setStatusCode(404);

        return $response;
    }


}