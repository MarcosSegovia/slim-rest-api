<?php

require_once __DIR__ . '/class-loader.php';

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__ . '/src/App/Entity'));
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir(__DIR__ . '/src/App/Entity/Proxy');
$config->setProxyNamespace('Proxy');

$ini = parse_ini_file(__DIR__ . '/config/local.ini');
$connectionOptions = array(
    'driver'   => $ini['driv'],
    'host'     => $ini['host'],
    'dbname'   => $ini['name'],
    'user'     => $ini['user'],
    'password' => $ini['pass'],
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
$conn =  $em->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($conn),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));