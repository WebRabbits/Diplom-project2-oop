<?php 

namespace App\ContainerBuilder;

use DI\ContainerBuilder;
use App\Database\Connection;
use Aura\SqlQuery\QueryFactory;
use PDO;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    PDO::class => function() {
        return Connection::Connect();
    },

    QueryFactory::class => function(){
        return new QueryFactory("mysql");
    }
]);

$container = $builder->build();

?>