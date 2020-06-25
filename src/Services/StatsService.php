<?php


namespace App\Services;


use App\Drivers\Interfaces\IUrlDriver;
use App\Drivers\Interfaces\IUserDriver;
use App\Entities\Stats;
use App\Services\Interfaces\IStatsService;

class StatsService implements IStatsService
{

  private IUserDriver $userDrive;
  private IUrlDriver $urlDrive;

  public function __construct(IUserDriver $user, IUrlDriver $url)
  {
    $this->userDrive = $user;
    $this->urlDrive = $url;
  }

  /**
   * @param string $user_id
   * @return Stats
   */
  public function getByUser(string $user_id): Stats
  {
    $user = $this->userDrive->find($user_id);
    return new Stats(
      $this->urlDrive->hits($user),
      $this->urlDrive->urlCount($user),
      $this->urlDrive->topUrls($user, 10)
    );
  }

  /**
   * @return Stats
   */
  public function get(): Stats
  {
    return new Stats(
      $this->urlDrive->hits(null),
      $this->urlDrive->urlCount(null),
      $this->urlDrive->topUrls(null, 10)
    );
  }
}