<?php

namespace App\Config;

use App\Controllers\BaseController;

class Route
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    private const CONTROLLER_KEYWORD = 'Controller';


    /**
     * @param $pattern
     */
    public static function resource($pattern)
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

        $path = isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : '/';

        $pathPattern = implode('.', array_filter(explode('/', $path), function ($key) {
            return $key % 2 == 0;
        }, ARRAY_FILTER_USE_KEY));

        if ($pathPattern === $pattern) {
            $controller = self::getController($pattern);

            list($pathParts, $pattenParts, $action) = self::getAction($path, $pattern);

            $params = self::getParams($pathParts, $pattenParts);

            call_user_func_array([$controller, $action], $params);
        }
    }

    /**
     * @param $pattern
     * @return string
     */
    public static function getController($pattern): string
    {
        $controllersNamespace = (new \ReflectionClass(BaseController::class))->getNamespaceName() . "\\";

        $controller = preg_replace_callback("/^[a-z]|\.[a-zA-Z]/", function ($matches) {
            return strtoupper(ltrim(reset($matches), '.'));
        }, $pattern);

        return $controllersNamespace . $controller . self::CONTROLLER_KEYWORD;
    }

    /**
     * @param string $path
     * @param $pattern
     * @return array
     */
    public static function getAction(string $path, $pattern): array
    {
        $pathParts = explode('/', $path);
        $pattenParts = explode('.', $pattern);
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case self::METHOD_GET:
                if (end($pattenParts) === end($pathParts)) {
                    $action = 'index';
                } else {
                    $action = 'get';
                }
                break;
            case self::METHOD_POST:
                $action = 'create';
                break;
            case self::METHOD_PATCH:
                $action = 'update';
                break;
            case self::METHOD_DELETE:
                $action = 'delete';
                break;
            default:
                header("HTTP/1.0 405 Method Not Allowed");
                echo "405 Method Not Allowed";
                exit();
        }

        return array($pathParts, $pattenParts, $action);
    }

    /**
     * @param $pathParts
     * @param $pattenParts
     * @return array
     */
    public static function getParams($pathParts, $pattenParts): array
    {
        $params = [];
        if (count($pathParts) > 1) {
            foreach ($pathParts as $pathPart) {
                if (!in_array($pathPart, $pattenParts)) {
                    $params[] = $pathPart;
                }
            }
        }
        return $params;
    }
}