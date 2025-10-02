<?php

function getPDO()
{
    define("USERNAME", "root");
    define("PASSWORD", "root");
    define("DB", "module2_diplom_project1");
    define("HOST", "mysql-8.2");

    $dsn = "mysql:host=" . HOST . ";dbname=" . DB;
    return new PDO($dsn, USERNAME, PASSWORD);
}
