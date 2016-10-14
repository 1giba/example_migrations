<?php

use Phpmig\Adapter;
use Pimple\Container;

/*
|
| Configurar seu banco de dados
|
*/
$host       = 'mysql.tray';
$database   = 'exemplo';
$username   = 'tray';
$password   = 'tray77';

$container = new Container();

$container['db'] = function () use ($host, $database, $username, $password) {
    $dbh = new PDO('mysql:dbname=' . $database . ';host=' . $host, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

$container['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['db'], 'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;