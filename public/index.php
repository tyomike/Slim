<?php

// Подключение автозагрузки через composer
namespace App;
require __DIR__ . '/../vendor/autoload.php';
use Slim\Factory\AppFactory;
use DI\Container;
$app = AppFactory::create();


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
});


$app->get('/users/new', function ($request, $response) {
    $params = [
        'user' => ['name' => '', 'email' => '', 'password' => '', 'passwordConfirmation' => '', 'city' => ''],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, $params);
})->setName('authorization');

//
$app->post('/users/new', function ($request, $response){

    $user = $request->getParsedBodyParam('user');

    $new = json_encode($user);
    file_put_contents('info.json', $new);

    return $this->get('renderer')->render($response, "users/authorization.phtml");
});

// Получаем роутер – объект отвечающий за хранение и обработку маршрутов
$router = $app->getRouteCollector()->getRouteParser();
// Не забываем прокинуть его в обработчик
$app->get('/', function ($request, $response) use ($router) {
    // в функцию передаётся имя маршрута, а она возвращает url
    // остальной код
    return $response->withRedirect($router->urlFor('authorization')); // /users/new)
});
$app->run();
