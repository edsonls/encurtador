<?php


namespace App\Services;


use App\Drivers\Interfaces\IUrlDriver;
use App\Entities\User;
use App\Services\Interfaces\IUrlService;
use App\Entities\Url;
use App\Utils\Encurtador;
use App\Utils\Exceptions\ConflictException;
use App\Utils\Exceptions\DataBaseException;

class UrlService implements IUrlService
{
  private IUrlDriver $drive;

  public function __construct(IUrlDriver $drive)
  {
    $this->drive = $drive;
  }

  public function getUrl(string $shortUrl): string
  {
    $url = $this->drive->findByShortUrl($shortUrl);
    $url->addHit();
    $this->update($url);
    return $url->getUrl();
  }

  /**
   * @param User $user
   * @param string $url
   * @return Url
   * @throws ConflictException
   * @throws DataBaseException
   */
  public function addUrl(User $user, string $url): Url
  {
    if ($this->drive->exist($user, $url)) {
      throw new ConflictException('Url já existente!');
    }
    $urlObject = new Url($user, 0, Encurtador::generateRandomString(6), $url);
    if (!$this->drive->save($urlObject)) {
      throw new DataBaseException('Erro DataBase');
    }
    return $urlObject;
  }

  public function update(Url $url): bool
  {
    return $this->drive->update($url);
  }

  /**
   * @param string $id
   * @return bool
   */
  public function delete(string $id): bool
  {
    return $this->drive->delete($id);
  }

  /**
   * @param User $user
   * @return bool
   */
  public function deleteUrlByUser(User $user): bool
  {
    return $this->drive->deleteUrlByUser($user);
  }


  public function find(string $id): Url
  {
    return $this->drive->find($id);
  }
}