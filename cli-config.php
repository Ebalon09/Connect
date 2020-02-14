<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/src'), $isDevMode, null, null, false);

$conn = array(
    'driver' => 'pdo_mysql',
    'user'   => 'ebalon',
    'password'   => 'root',
    'dbname'    => 'Twitter'
);

$entityManager = EntityManager::create($conn, $config);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);