<?php

namespace App\ContainerBuilder;

use App\Services\ImageUploadService;
use DI\ContainerBuilder;
use App\Database\Connection;
use Aura\SqlQuery\QueryFactory;
use League\Plates\Engine;
use App\Services\ValidationService;
use App\Services\PasswordHasher;
use PDO;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    PDO::class => function () {
        return Connection::Connect();
    },

    QueryFactory::class => function () {
        return new QueryFactory("mysql");
    },

    ValidationService::class => function () {
        return new ValidationService();
    },

    PasswordHasher::class => function () {
        return new PasswordHasher();
    },

    Engine::class => function () {
        return new Engine("../app/Views");
    },

    ImageUploadService::class => function () {
        return new ImageUploadService(
            targetDirectory: dirname($_SERVER["DOCUMENT_ROOT"]) . "/public/img/posts/",
            publicDirectory: "/img/posts/"
        );
    }
]);

$container = $builder->build();
