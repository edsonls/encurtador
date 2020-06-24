<?php

namespace App\Services;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\Url;
use App\Entities\User;
use App\Services\Interfaces\IUserService;
use App\Utils\Encurtador;
use App\Utils\Exceptions\ConflictException;
use App\Utils\Exceptions\DataBaseException;

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

  /**
   * @param string $user_id
   * @param string $url
   * @return Url
   * @throws ConflictException
   * @throws DataBaseException
   */
  public function addUrl(string $user_id, string $url): Url
  {
    $user = $this->drive->find($user_id);
    if(!$this->drive->validUrl($user,$url)){
      throw new ConflictException('Url já existente!');
    }
    $urlObject = new Url(0, Encurtador::generateRandomString(6), $url);
    $user->addUrl($urlObject);
    if (!$this->drive->update($user)) {
      throw new DataBaseException('Erro DataBase');
    }
    return $urlObject;
  }
}