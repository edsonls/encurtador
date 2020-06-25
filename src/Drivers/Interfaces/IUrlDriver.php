<?php


namespace App\Drivers\Interfaces;


use App\Entities\Url;
use App\Entities\User;

interface IUrlDriver
{
  public function find(string $shortUrl): Url;

  public function save(Url $url): bool;
  
  public function exist(User $user, string $url): bool;

  public function update(Url $url): bool;
}