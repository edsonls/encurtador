<?php


namespace App\Entities;

use Ramsey\Uuid\Uuid;

class Url
{
  private string $id;
  private User $user;
  private int $hits;
  private string $shortUrl;
  private string $url;

  /**
   * Url constructor.
   * @param User $user
   * @param int $hits
   * @param string $shortUrl
   * @param string $url
   * @param string|null $id
   */
  public function __construct(User $user, int $hits, string $shortUrl, string $url, string $id = null)
  {
    $this->user = $user;
    $this->id = $id ?? Uuid::uuid4()->toString();
    $this->hits = $hits;
    $this->shortUrl = $shortUrl;
    $this->url = $url;
  }

  /**
   * @return User
   */
  public function getUser(): User
  {
    return $this->user;
  }

  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * @return int
   */
  public function getHits(): int
  {
    return $this->hits;
  }

  /**
   * @return string
   */
  public function getShortUrl(): string
  {
    return $this->shortUrl;
  }

  /**
   * @return string
   */
  public function getUrl(): string
  {
    return $this->url;
  }

  public function addHit(): void
  {
    $this->hits++;
  }


}