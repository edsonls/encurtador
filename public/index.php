<?php

if (getenv('DEBUG')) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

use App\Controllers\StatsController;
use App\Controllers\UrlController;
use App\Controllers\UserController;
use App\Utils\Exceptions\ConflictException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
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
      $userController = new UserController();
      $user = $userController->getUser($args['id']);
      $url = new UrlController();
      $urlStr = json_encode(
        $url->addUrl($user, $request->getBody()),
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
  '/users/{user_id}/stats',
  static function (Request $request, Response $response, $args) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    try {
      $stats = new StatsController();
      $statsObj = $stats->getByUser($args['user_id']);
      $resp->getBody()->write(
        json_encode(
          [
            'hits' => $statsObj->getHits(),
            'urlCount' => $statsObj->getUrlCount(),
            'topUrls' => $statsObj->getTopUrls(),
          ],
          JSON_THROW_ON_ERROR
        )
      );
      return $resp->withStatus(200);
    } catch (Exception $exception) {
      return $response->withStatus(404);
    }
  }
);
$app->get(
  '/stats',
  static function (Request $request, Response $response, $args) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    try {
      $stats = new StatsController();
      $statsObj = $stats->get();
      $resp->getBody()->write(
        json_encode(
          [
            'hits' => $statsObj->getHits(),
            'urlCount' => $statsObj->getUrlCount(),
            'topUrls' => $statsObj->getTopUrls(),
          ],
          JSON_THROW_ON_ERROR
        )
      );
      return $resp->withStatus(200);
    } catch (Exception $exception) {
      return $response->withStatus(404);
    }
  }
);
$app->get(
  '/stats/{id}',
  static function (Request $request, Response $response, $args) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    try {
      $urlController = new UrlController();
      $urlObj = $urlController->get($args['id']);
      $resp->getBody()->write(
        json_encode(
          $urlObj,
          JSON_THROW_ON_ERROR
        )
      );
      return $resp->withStatus(200);
    } catch (Exception $exception) {
      return $response->withStatus(404);
    }
  }
);

$app->delete(
  '/urls/{id}',
  static function (Request $request, Response $response, $args) {
    try {
      $url = new UrlController();
      $url->deleteUrl($args['id']);
      return $response
        ->withStatus(204);
    } catch (Exception $exception) {
      return $response->withStatus(404);
    }
  }
);
$app->delete(
  '/users/{id}',
  static function (Request $request, Response $response, $args) {
    try {
      $userController = new UserController();
      $url = new UrlController();
      $url->deleteUrlByUser($userController->getUser($args['id']));
      $userController->delete($args['id']);
      return $response
        ->withStatus(204);
    } catch (Exception $exception) {
      return $response->withStatus(404);
    }
  }
);

$app->get(
  '/{id}',
  static function (Request $request, Response $response, $args) {
    try {
      $url = new UrlController();
      return $response
        ->withHeader('Location', $url->getUrl($args['id']))
        ->withStatus(301);
    } catch (Exception $exception) {
      return $response->withStatus(404);
    }
  }
);

$app->get(
  '/',
  static function (Request $request, Response $response, $args) {
    $resp = $response
      ->withHeader('Content-Type', 'application/json');
    return $resp
      ->getBody()->write('*-*');
  }
);

$app->run();
