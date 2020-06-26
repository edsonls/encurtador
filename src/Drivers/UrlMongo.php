<?php


namespace App\Drivers;


use App\Drivers\Interfaces\IUrlDriver;
use App\Entities\Url;
use App\Entities\User;
use App\Providers\DataBase\MongoClient;
use App\Utils\Exceptions\DataBaseException;
use Exception;
use MongoDB\Collection;

class UrlMongo extends MongoClient implements IUrlDriver
{
  private Collection $collection;

  public function __construct()
  {
    $this->collection = $this->getConnection()->selectCollection('Urls');
  }

  public function findByShortUrl(string $shortUrl): Url
  {
    $urlMongo = $this->collection->findOne(['shortUrl' => $shortUrl]);
    if (empty($urlMongo)) {
      throw new DataBaseException('Url não encontrada');
    }
    return new Url (new User($urlMongo->user_id), $urlMongo->hits, $shortUrl, $urlMongo->url, $urlMongo->id);
  }

  public function save(Url $url): bool
  {
    return $this->collection->insertOne(
      [
        "hits" => $url->getHits(),
        "id" => $url->getId(),
        "shortUrl" => $url->getShortUrl(),
        "url" => $url->getUrl(),
        "user_id" => $url->getUser()->getId(),
      ]
    )->isAcknowledged();
  }

  public function exist(User $user, string $url): bool
  {
    return $this->collection->countDocuments(['user_id' => $user->getId(), 'url' => $url]) > 0;
  }

  public function update(Url $url): bool
  {
    return $this->collection->updateOne(
      ["id" => $url->getId()],
      [
        '$set' => [
          "hits" => $url->getHits(),
          "shortUrl" => $url->getShortUrl(),
          "url" => $url->getUrl(),
          "user_id" => $url->getUser()->getId(),
        ],
      ]
    )->isAcknowledged();
  }

  public function findAll(User $user): ?array
  {
    $urlsMongo = $this->collection->find(['user_id' => $user->getId()])->toArray();
    if (empty($urlsMongo)) {
      return null;
    }
    $urls = [];
    foreach ($urlsMongo as $url) {
      $urls[] = new Url ($user, $url->hits, $url->shortUrl, $url->url, $url->id);
    }
    return $urls;
  }

  public function hits(?User $user): int
  {
    try {
      if (is_null($user)) {
        $re = $this->collection->aggregate(
          [
            ['$group' => ['_id' => null, 'total' => ['$sum' => '$hits']]],
            ['$sort' => ['count' => -1]],
            ['$limit' => 5],
          ]
        );
      } else {
        $re = $this->collection->aggregate(
          [
            [
              '$match' =>
                ['user_id' => $user->getId()],
            ],
            ['$group' => ['_id' => null, 'total' => ['$sum' => '$hits']]],
            ['$sort' => ['count' => -1]],
            ['$limit' => 5],
          ]
        );
      }
      foreach ($re as $state) {
        return ($state['total']);
      }
    } catch (Exception $exception) {
      var_dump($exception);
    }
  }

  public function urlCount(?User $user): int
  {
    if (is_null($user)) {
      return $this->collection->countDocuments();
    }
    return $this->collection->countDocuments(['user_id' => $user->getId()]);
  }

  /**
   * @param User|null $user
   * @param int $limit
   * @return array
   */
  public function topUrls(?User $user, int $limit): array
  {
    if (is_null($user)) {
      $urlsMongo = $this->collection->find(
        [],
        [
          'limit' => $limit,
          'sort' => ['hits' => -1],
        ]
      );
    } else {
      $urlsMongo = $this->collection->find(
        ['user_id' => $user->getId()],
        [
          'limit' => $limit,
          'sort' => ['hits' => -1],
        ]
      );
    }
    $urls = [];
    foreach ($urlsMongo as $url) {
      $urls[] = new Url ($user, $url->hits, $url->shortUrl, $url->url, $url->id);
    }
    return $urls;
  }

  /**
   * @param string $id
   * @return bool
   * @throws DataBaseException
   */
  public function delete(string $id): bool
  {
    $deleteResult = $this->collection->deleteOne(['id' => $id]);
    if ($deleteResult->getDeletedCount() <= 0) {
      throw new DataBaseException('Url não existente');
    }
    return true;
  }

  /**
   * @param User $user
   * @return bool
   * @throws DataBaseException
   */
  public function deleteUrlByUser(User $user): bool
  {
    $deleteResult = $this->collection->deleteMany(['user_id' => $user->getId()]);
    if ($deleteResult->getDeletedCount() <= 0) {
      throw new DataBaseException('Url não existente');
    }
    return true;
  }

  public function find(string $id): Url
  {
    $urlMongo = $this->collection->findOne(['id' => $id]);
    if (empty($urlMongo)) {
      throw new DataBaseException('Url não encontrada');
    }
    return new Url (new User($urlMongo->user_id), $urlMongo->hits, $urlMongo->shortUrl, $urlMongo->url, $urlMongo->id);
  }
}