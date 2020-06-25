<?php


namespace App\Drivers;


use App\Drivers\Interfaces\IUrlDriver;
use App\Entities\Url;
use App\Entities\User;
use App\Providers\DataBase\MongoClient;
use Exception;
use MongoDB\Collection;

class UrlMongo extends MongoClient implements IUrlDriver
{
  private Collection $collection;

  public function __construct()
  {
    $this->collection = $this->getConnection()->selectCollection('Urls');
  }

  public function find(string $shortUrl): Url
  {
    $urlMongo = $this->collection->findOne(['shortUrl' => $shortUrl]);
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
}