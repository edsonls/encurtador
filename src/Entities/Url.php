<?php


namespace App\Entity;

use Ramsey\Uuid\Uuid;

class Url
{
  private string $id;
  private int $hits;
  private string $shortUrl;
  private string $url;

  /**
   * Url constructor.
   * @param int $hits
   * @param string $shortUrl
   * @param string $url
   */
  public function __construct(int $hits, string $shortUrl, string $url)
  {
    $this->id = Uuid::uuid4()->toString();
    $this->hits = $hits;
    $this->shortUrl = $shortUrl;
    $this->url = $url;
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


}