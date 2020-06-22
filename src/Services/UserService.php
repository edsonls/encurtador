<?php

namespace App\Services;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\Url;
use App\Entities\User;
use App\Services\Interfaces\IUserService;
use App\Utils\Encurtador;

class UserService implements IUserService
{
  private IUserDriver $drive;

  public function __construct(IUserDriver $drive)
  {
    $this->drive = $drive;
  }

  public function add(string $id): bool
  {
    $user = new User($id);
    return $this->drive->save($user);
  }

  public function addUrl(string $user_id, string $url): Url
  {
    $user = $this->drive->find($user_id);
    $urlObject = new Url(0, Encurtador::generateRandomString(6), $url);
    $user->addUrl($urlObject);
    if ($this->drive->update($user)) {
      return $urlObject;
    }

  }
}