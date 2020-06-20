<?php

use App\Controllers\UrlController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get(
  '/{id}',
  static function (Request $request, Response $response, $args) {
    $response->getBody()->write(UrlController::get($args['id']));
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
);

$app->run();
