<?php


namespace App\Drivers\Interfaces;


use App\Entity\User;

interface IUserDriver
{
  public function save(User $user): bool;
}