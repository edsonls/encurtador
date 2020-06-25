<?php

namespace App\Controllers;

use App\Drivers\UrlMongo;
use App\Drivers\UserMongo;
use App\Entities\Stats;
use App\Services\Interfaces\IStatsService;
use App\Services\StatsService;

class StatsController
{
  private IStatsService $service;

  public function __construct()
  {
    $this->service = new StatsService(new UserMongo(), new UrlMongo());
  }

  /**
   * @param $user_id
   * @return Stats
   */
  public function getByUser($user_id): Stats
  {
    return $this->service->getByUser($user_id);
  }

  /**
   * @return Stats
   */
  public function get(): Stats
  {
    return $this->service->get();
  }


}