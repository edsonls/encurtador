<?php

namespace App\Drivers;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\User;
use App\Providers\DataBase\MongoClient;
use App\Utils\Exceptions\ConflictException;
use App\Utils\Exceptions\DataBaseException;
use MongoDB\Collection;

class UserMongo extends MongoClient implements IUserDriver
{
  private Collection $collection;

  public function __construct()
  {
    $this->collection = $this->getConnection()->selectCollection('User');
  }

  /**
   * @param User $user
   * @return bool
   * @throws ConflictException
   */
  public function save(User $user): bool
  {
    if ($this->exist($user->getId())) {
      throw new ConflictException('Usuario já cadastrado');
    }
    $this->collection->insertOne(
      [
        'id' => $user->getId(),
      ]
    );
    return true;
  }

  private function exist(string $id): bool
  {
    return $this->collection->countDocuments(['id' => $id]) > 0;
  }

  public function find(string $id): User
  {
    $userMongo = $this->collection->findOne(['id' => $id]);
    if (empty($userMongo)) {
      throw new DataBaseException('User Not Found');
    }
    return new User($userMongo->id);
  }

  public function validUrl(User $user, string $url): bool
  {
    foreach ($user->getUrl() as $item) {
      if ($item->getUrl() === $url) {
        return false;
      }
    }
    return true;
  }
}