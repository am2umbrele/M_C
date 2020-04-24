<?php

namespace App\Models;

use App\Config\DB;
use App\Config\Route;
use PDO;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Model
{
    const VERB_GET = 'get';
    const VERB_SET = 'set';

    /**
     * @param $params
     * @return mixed
     */
    public static function findOneBy($params)
    {
        $modelClass = get_called_class();
        $tableName = get_class_vars($modelClass)["tableName"];

        list($where, $paramsKeys) = self::getFindWhere($params);

        $sql = "SELECT * FROM $tableName WHERE $where"; // TODO limit 1

        $model = DB::getInstance()->getConnection()->query($sql)->fetchObject($modelClass);;
        // TODO

        if (!$model) {
            echo "No results found for $modelClass";
            exit();
        }

        return $model;
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        $tableName = self::getTableName();

        $sql = "SELECT * FROM $tableName";

        return DB::getInstance()->getConnection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $params
     * @return array
     */
    public static function getWhere(array $params): array
    {
        $tableName = self::getTableName();

        list($where, $paramsKeys) = self::getFindWhere($params);

        $sql = "SELECT * FROM $tableName WHERE $where";

        return DB::getInstance()->getConnection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function doSave($model)
    {
        $tableName = self::getTableName();

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        try {
            $reflect = new ReflectionClass($model);
        } catch (ReflectionException $e) {
            echo "Class " . get_class($model) . " not found: ", $e->getMessage(), "\n";
            exit();
        }

        $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);

        $fieldNames = '';
        $fieldValues = '';
        $primaryKeyValue = '';
        $set = '';

        if (property_exists($model, 'autoGenerateValue') &&
            self::snakeCase(end($props)->name) === $model->autoGenerateValue) {
            array_pop($props); // TODO remove the searched element instead of the last element
        }

        $lastProp = end($props);
        foreach ($props as $key => $prop) {
            list($propName, $method, $fieldName) = self::getProperties($prop);

            if (in_array($method, get_class_methods($model)) && is_callable([$model, "$method"])) {
                $getPropertyValue = call_user_func([$model, "$method"]);

                if (property_exists($model, 'primaryKey') && $propName === $model->primaryKey) {
                    $primaryKeyValue = $getPropertyValue;
                } else {
                    $fieldNames .= $fieldName;
                    $fieldValues .= "'" . $getPropertyValue . "'";
                    $set .= $fieldName . " = '" . $getPropertyValue . "'";

                    if ($prop !== $lastProp) {
                        $fieldNames .= ",";
                        $fieldValues .= ",";
                        $set .= ",";
                    }

                }
            }
        }

        if ($requestMethod === Route::METHOD_POST) {
            $sql = "INSERT INTO $tableName ($fieldNames) VALUES ($fieldValues)";
        } else {
            $sql = "UPDATE $tableName SET $set WHERE $model->primaryKey = $primaryKeyValue";
        }

        DB::getInstance()->getConnection()->exec($sql);
    }

    public static function doDelete($model)
    {
        $tableName = self::getTableName();

        $searchColumn = $model->primaryKey;

        $method = self::getClassMethod($searchColumn, self::VERB_GET);
        $modelId = call_user_func([$model, "$method"]);
        $where = $searchColumn . " = '" . $modelId . "'";

        $sql = "DELETE FROM $tableName WHERE $where";

        DB::getInstance()->getConnection()->exec($sql);
    }

    /**
     * @param string $propName
     * @param string $verb
     * @return string
     */
    private static function getClassMethod(string $propName, string $verb): string
    {
        return $verb . str_replace('_', '', ucwords($propName, '_'));
    }

    /**
     * @param $propName
     * @return string
     */
    private static function snakeCase($propName): string
    {
        return strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $propName));
    }

    /**
     * @param $params
     * @return array
     */
    private static function getFindWhere($params): array
    {
        $where = '';
        $paramsKeys = array_keys($params);

        foreach ($params as $key => $param) {
            if ($key === reset($paramsKeys)) {
                $where .= " $key = '$param'";
            } else {
                $where .= " AND $key = '$param'";
            }
        }
        return array($where, $paramsKeys);
    }

    /**
     * @param ReflectionProperty $prop
     * @return array
     */
    private static function getProperties(ReflectionProperty $prop): array
    {
        $propName = $prop->getName();

        $method = self::getClassMethod($propName, self::VERB_GET);
        $fieldName = self::snakeCase($propName);
        return array($propName, $method, $fieldName);
    }

    /**
     * @return mixed
     */
    private static function getTableName()
    {
        return get_class_vars(get_called_class())["tableName"];
    }
}