<?php


namespace App\Drivers;


use App\Drivers\Interfaces\IUserDriver;
use App\Entity\User;

class UserMongo implements IUserDriver
{

  public function save(User $user): bool
  {
    // TODO: Implement save() method.
  }
}