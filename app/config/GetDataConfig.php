<?php

namespace App\Config;

use Exception;

require_once(__DIR__ . "/../config/GlobalsConfig.php");

class GetDataConfig
{
    public static function Get(string $path = "")
    {
        if (isset($path)) {
            $config = $GLOBALS["config"];

            $path = explode(".", $path);

            foreach ($path as $key) {
                if (isset($config[$key])) {
                    $config = $config[$key];
                }
            }

            try {
                if (is_array($config)) {
                    throw new Exception("Ошибка. Переданное значение является массивом");
                }

                return $config;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
