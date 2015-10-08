<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$isDevMode = true;

$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

$db_config = include 'db-config.php';

// database configuration parameters
$connectionOptions = array(
    'driver'   => 'pdo_mysql',
    'path'	   => __DIR__ . '/mysql.php',
    'host'     => '127.0.0.1',
    'dbname'   => $db_config['database'],
    'user'     => $db_config['username'],
    'password' => $db_config['password']
);

// obtaining the entity manager
$entityManager = EntityManager::create($connectionOptions, $config);