<?php

namespace App\Drivers;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\User;

class UserMongo implements IUserDriver
{

  public function save(User $user): bool
  {
    return true;
  }
}