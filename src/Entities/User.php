<?php


namespace App\Entities;

class User
{
  private string $id;
  /**
   * @var Url[]
   */
  private ?array $url;

  /**
   * User constructor.
   * @param string $id
   * @param array|null $urls
   */
  public function __construct(string $id, array $urls = null)
  {
    $this->id = $id;
    $this->url = $urls;
  }

  /**
   * @param Url[] $url
   */
  public function setUrl(array $url): void
  {
    $this->url = $url;
  }

  /**
   * @param Url $url
   */
  public function addUrl(Url $url): void
  {
    $this->url[] = $url;
  }

  /**
   * @return Url[]
   */
  public function getUrl(): array
  {
    return $this->url ?? [];
  }

  public function getId(): string
  {
    return $this->id;
  }

}