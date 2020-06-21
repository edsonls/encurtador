<?php


namespace App\Controllers;


use App\Drivers\UserMongo;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
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
   */
  public function add(StreamInterface $request): bool
  {
    $body = $request;
    var_dump($body); die();
    //return $this->service->add();
  }
}