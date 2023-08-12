<?php
require 'public/index.php';

$migrations = [];
$seeds = [];
foreach ($app->getModules() as $module) {
    if ($module::MIGRATIONS) {
        $migrations[] = $module::MIGRATIONS;
    }
    if ($module::SEEDS) {
        $seeds[] = $module::SEEDS;
    }
}

return [
    'paths' => [
        'migrations' => $migrations,
        'seeds'      => $seeds
    ],
    'environments' => [
        'default_database' => 'development',
        'development'      => [
            'adapter'=> 'mysql',
            'host'   => $container->get('database.host'),
            'name'   => $container->get('database.name'),
            'user'   => $container->get('database.username'),
            'pass'   => $container->get('database.password')
        ]
    ]
];
