<?php


namespace App\Drivers\Interfaces;


use App\Entities\User;

interface IUserDriver
{
  public function save(User $user): bool;

  public function find(string $user_id): User;

  public function update(User $user): bool;
}