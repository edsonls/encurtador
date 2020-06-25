<?php

namespace App\Services\Interfaces;

use App\Drivers\Interfaces\IUrlDriver;
use App\Entities\Url;
use App\Entities\User;

interface IUrlService
{
  public function __construct(IUrlDriver $url);

  public function getUrl(string $shortUrl): string;

  public function addUrl(User $user, string $url): Url;

  public function delete(string $id): bool;
}