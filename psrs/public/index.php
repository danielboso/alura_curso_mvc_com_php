<?php

require __DIR__ . '/../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$caminho = $_SERVER['PATH_INFO'];
$rotas = require __DIR__ . '/../config/routes.php';

if(!array_key_exists($caminho, $rotas)) {
    http_response_code(404);
    exit();
}

session_start();

// $rotaLogin = stripos($caminho, 'login');
// if(!isset($_SESSION['logado']) && $rotaLogin === false) {
    // header('Location: /login');
    // return;
// }

$psr17Factory = New Psr17Factory();

$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$request = $creator->fromGlobals();

$classeControladora = $rotas[$caminho];
$container = require __DIR__ . '/../config/dependencies.php';
$controlador = $container->get($classeControladora);
$resposta = $controlador->handle($request);

foreach ($resposta->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $resposta->getBody();