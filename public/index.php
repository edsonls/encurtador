<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controllers\UrlController;
use App\Controllers\UserController;
use App\Utils\Exceptions\ConflictException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->post(
  '/users',
  static function (Request $request, Response $response) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    try {
      $user = new UserController();
      $user->add($request->getBody());
      return $resp->withStatus(201);
    } catch (ConflictException $exception) {
      return $resp->withStatus(409);
    } catch (Exception $exception) {
      return $resp->withStatus(400);
    }
  }
);
$app->post(
  '/users/{id}/urls',
  static function (Request $request, Response $response, $args) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    try {
      $user = new UserController();
      $url = $user->addUrl($args['id'], $request->getBody());
      $urlStr = json_encode(
        [
          'hits' => $url->getHits(),
          'shortUrl' => $url->getShortUrl(),
          'id' => $url->getId(),
          'url' => $url->getUrl(),
        ],
        JSON_THROW_ON_ERROR
      );
      $resp->getBody()->write($urlStr);
      return $resp->withStatus(201);
    } catch (ConflictException $exception) {
      return $resp->withStatus(409);
    } catch (Exception $exception) {
      return $resp->withStatus(400);
    }
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
