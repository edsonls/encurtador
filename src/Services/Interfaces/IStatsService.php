<?php

namespace App\Services\Interfaces;

use App\Drivers\Interfaces\IUrlDriver;
use App\Drivers\Interfaces\IUserDriver;

interface IStatsService
{
  public function __construct(IUserDriver $user,IUrlDriver $url);
}