<?php

namespace App\Services\Interfaces;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\Url;
use App\Entities\User;

interface IUserService
{
  public function __construct(IUserDriver $user);

  public function add(string $id): bool;

  public function addUrl(string $user_id, string $url): Url;

  public function getUser(string $id): User;
}