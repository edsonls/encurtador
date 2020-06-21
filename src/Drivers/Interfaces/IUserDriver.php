<?php


namespace App\Drivers\Interfaces;


use App\Entities\User;

interface IUserDriver
{
  public function save(User $user): bool;
}