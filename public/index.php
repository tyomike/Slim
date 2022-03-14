<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);


$app->get('/users', function ($request, $response) {
    $term = $request->getQueryParam('term');
    $users = ['lisa', 'mishel', 'adel', 'keks', 'kamila'];

    foreach ($users as $user)
    {
            $result = [];
            if (strpos($term, $user) == true)
            {
                array_push($result, $users);
            }
    }

    $params = ['term' => $term,
               'users' => $users
        ];
    return $this->get('renderer')->render($response, 'users/index1.phtml', $params);
});
$app->run();
