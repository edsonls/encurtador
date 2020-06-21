<?php

namespace App\Drivers;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\User;
use App\Providers\DataBase\MongoClient;
use Exception;
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
   * @throws Exception
   */
  public function save(User $user): bool
  {
    if ($this->exist($user->getId())) {
      throw new Exception('Usuario já cadastrado');
    }
    $this->collection->insertOne(
      [
        'id' => $user->getId(),
        'urls' => $user->getUrl(),
      ]
    );
    return true;
  }

  private function exist(string $id): bool
  {
    return !empty($this->collection->find(['id' => $id])->toArray());
  }
}