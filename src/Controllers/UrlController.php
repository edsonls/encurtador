<?php

namespace App\Controllers;

use App\Drivers\UrlMongo;
use App\Entities\Url;
use App\Entities\User;
use App\Services\Interfaces\IUrlService;
use App\Services\UrlService;
use App\Utils\Exceptions\ConflictException;
use App\Utils\Exceptions\DataBaseException;
use App\Utils\Exceptions\RequestException;
use JsonException;
use Psr\Http\Message\StreamInterface;

class UrlController
{
  private IUrlService $service;

  public function __construct()
  {
    $this->service = new UrlService(new UrlMongo());
  }

  public function getUrl($shortUrl): string
  {
    return $this->service->getUrl($shortUrl);
  }

  /**
   * @param User $user
   * @param StreamInterface $request
   * @return Url
   * @throws RequestException
   * @throws ConflictException
   * @throws DataBaseException
   * @throws JsonException
   */
  public function addUrl(User $user, StreamInterface $request): Url
  {
    $body = json_decode($request->__toString(), true, 512, JSON_THROW_ON_ERROR);
    if (!array_key_exists('url', $body)) {
      throw new RequestException('Body Inválido!');
    }
    return $this->service->addUrl($user, $body['url']);
  }


}