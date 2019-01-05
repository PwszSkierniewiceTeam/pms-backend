<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// db instance
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO(
        "mysql:host=" . $db['host'] .
        ";dbname=" . $db['dbname'] .
        ";port=" . $db['port'],
        $db['user'],
        $db['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

//Override the default error handlers
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $data = ['data' => 'Resource not found'];
        return $c['response']->withJson($data, 404);
    };
};

/*$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        $data = array('data' => 'Internal server error');
        return $c['response']->withJson($data, 500);
    };
};*/