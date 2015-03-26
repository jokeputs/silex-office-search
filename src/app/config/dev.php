<?php

// Doctrine
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'dbname'   => 'excersise',
    'user'     => 'root',
    'password' => 'root',
);

$app['debug'] = true;