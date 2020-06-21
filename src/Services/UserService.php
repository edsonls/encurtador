<?php
namespace App\Services;

use App\Drivers\Interfaces\IUserDriver;
use App\Entities\User;
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
    $user = new User($id);
    return $this->drive->save($user);
  }
}