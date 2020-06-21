<?php

use App\Controllers\UrlController;
use App\Controllers\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->post(
  '/users',
  static function (Request $request, Response $response) {
    $user = new UserController();
    $user->add($request->getBody());
  }
);

$app->get(
  '/{id}',
  static function (Request $request, Response $response, $args) {
    $response->getBody()->write(UrlController::get($args['id']));
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
);

$app->run();
