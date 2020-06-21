<?php


namespace App\Services;


use App\Drivers\Interfaces\IUserDriver;
use App\Services\Interfaces\IUserService;

class UserService implements IUserService
{
  private IUserDriver $drive;

  public function __construct(IUserDriver $drive)
  {
    $this->drive = $drive;
  }

  public function add(string $id): bool
  {
    // TODO: Implement add() method.
  }
}