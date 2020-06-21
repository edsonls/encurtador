<?php


namespace App\Services\Interfaces;

use App\Drivers\Interfaces\IUserDriver;

interface IUserService
{
  public function __construct(IUserDriver $user);

  public function add(string $id): bool;
}