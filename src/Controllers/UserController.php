<?php

namespace App\Controllers;

use App\Drivers\UserMongo;
use App\Entities\Url;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use App\Utils\Exceptions\RequestException;
use Exception;
use JsonException;
use Psr\Http\Message\StreamInterface;

class UserController
{
  private IUserService $service;

  public function __construct()
  {
    $this->service = new UserService(new UserMongo());
  }

  /**
   * @param StreamInterface $request
   * @return bool
   * @throws JsonException
   * @throws Exception
   */
  public function add(StreamInterface $request): bool
  {
    $body = json_decode($request->__toString(), true, 512, JSON_THROW_ON_ERROR);
    if (!array_key_exists('id', $body)) {
      throw new RequestException('Body Inválido!');
    }
    return $this->service->add($body['id']);
  }

  /**
   * @param string $user_id
   * @param StreamInterface $request
   * @return Url
   * @throws JsonException
   * @throws RequestException
   */
  public function addUrl(string $user_id, StreamInterface $request): Url
  {
    $body = json_decode($request->__toString(), true, 512, JSON_THROW_ON_ERROR);
    if (empty($user_id) || !array_key_exists('url', $body)) {
      throw new RequestException('Body Inválido!');
    }
    return $this->service->addUrl($user_id, $body['url']);
  }
}