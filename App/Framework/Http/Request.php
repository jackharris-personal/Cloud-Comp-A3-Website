<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - nextstats-auth
 * Last Updated - 6/11/2023
 */

namespace App\Core\Http;

class Request
{

    public function GetHttpUserAgent(): ?string
    {
        return $_SERVER["HTTP_USER_AGENT"] ?? null;
    }

    public function GetAuthorizationHeader(): ?string{

        return null;
    }


}