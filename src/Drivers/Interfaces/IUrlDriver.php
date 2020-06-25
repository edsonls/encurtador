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

  /**
   * @param User $user
   * @return Url[]
   */
  public function findAll(User $user): ?array;

  public function hits(?User $user): int;

  public function urlCount(?User $user): int;

  /**
   * @param User $user
   * @param int $int
   * @return Url[]
   */
  public function topUrls(?User $user, int $int): array;
}