<?php


namespace App\Drivers;


use App\Drivers\Interfaces\IUrlDriver;
use App\Entities\Url;
use App\Entities\User;
use App\Providers\DataBase\MongoClient;
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
}