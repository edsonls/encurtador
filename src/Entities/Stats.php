<?php


namespace App\Entities;


class Stats
{
  private int $hits;
  private int $urlCount;
  /**
   * @var Url[]
   */
  private array $topUrls;

  /**
   * Stats constructor.
   * @param int $hits
   * @param int $urlCount
   * @param Url[]|array $topUrls
   */
  public function __construct(int $hits, int $urlCount, $topUrls)
  {
    $this->hits = $hits;
    $this->urlCount = $urlCount;
    $this->topUrls = $topUrls;
  }

  /**
   * @return int
   */
  public function getHits(): int
  {
    return $this->hits;
  }

  /**
   * @return int
   */
  public function getUrlCount(): int
  {
    return $this->urlCount;
  }

  /**
   * @return Url[]
   */
  public function getTopUrls(): array
  {
    return $this->topUrls;
  }


}