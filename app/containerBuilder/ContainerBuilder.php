<?php 

namespace App\ContainerBuilder;

use DI\ContainerBuilder;
use App\Database\Connection;
use Aura\SqlQuery\QueryFactory;
use App\Services\ValidationService;
use App\Services\PasswordHasher;
use PDO;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    PDO::class => function() {
        return Connection::Connect();
    },

    QueryFactory::class => function(){
        return new QueryFactory("mysql");
    },

    ValidationService::class => function(){
        return new ValidationService();
    },

    PasswordHasher::class => function () {
        return new PasswordHasher();
    }
]);

$container = $builder->build();

?>