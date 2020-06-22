<?php

namespace App\Drivers;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\Url;
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
        'urls' => $this->toArrayUrlObject($user->getUrl()),
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
    $url = $this->toObjectUrl($userMongo->urls);
    return new User($userMongo->id, $url);
  }

  public function update(User $user): bool
  {
    $this->collection->updateOne(
      [
        'id' => $user->getId(),
      ],
      [
        '$set' => [
          'urls' => $this->toArrayUrlObject($user->getUrl()),
        ],
      ]
    );
    return true;
  }

  /**
   * @param Url[] $url
   * @return array
   */
  private function toArrayUrlObject(array $url): array
  {
    $aux = [];
    foreach ($url as $item) {
      $aux[] = [
        'hits' => $item->getHits(),
        'id' => $item->getId(),
        'url' => $item->getUrl(),
        'shortUrl' => $item->getShortUrl(),
      ];
    }
    return $aux;
  }

  private function toObjectUrl($urls): array
  {
    $aux = [];
    foreach ($urls as $item) {
      $aux[] = new Url($item->hits, $item->shortUrl, $item->url, $item->id);
    }
    return $aux;
  }
}