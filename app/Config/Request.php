<?php

namespace App\Config;

use stdClass;

class Request
{
    public static function all()
    {
        $rawRequest = json_decode(file_get_contents("php://input"), true);

        $request = new stdClass();
        foreach ($rawRequest as $key => $value) {
            $key = lcfirst(str_replace('_', '', ucwords($key, '_')));
            $request->$key = $value;
        }

        return $request;
    }
}