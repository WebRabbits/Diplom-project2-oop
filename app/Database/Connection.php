<?php 
namespace App\Database;
use App\Config\GetDataConfig;
use PDO;

class Connection{
    public static function Connect(): PDO{
        return new PDO("mysql:host=" . GetDataConfig::Get("mysql.host") . ";dbname=" . GetDataConfig::Get("mysql.db"), GetDataConfig::Get("mysql.username"), GetDataConfig::Get("mysql.password"));
    }
}

?>