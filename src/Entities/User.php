<?php


namespace App\Entity;


class User
{
  private string $id;
  /**
   * @var Url[]
   */
  private array $url;

  /**
   * User constructor.
   * @param string $id
   */
  public function __construct(string $id)
  {
    $this->id = $id;
  }

  /**
   * @param Url $url
   */
  public function addUrl(Url $url)
  {
    $this->url[] = $url;
  }

  /**
   * @return Url[]
   */
  public function getUrl(): array
  {
    return $this->url;
  }

}