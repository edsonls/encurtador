<?php


namespace App\Providers\DataBase;

use MongoDB\Client as Mongo;
use MongoDB\Database;

abstract class MongoClient
{
  private Database $con;
  private string $dataBase = 'teste';

  public function getConnection(): Database
  {
    if (empty($this->con)) {
      $con = new Mongo("mongodb://{$_ENV['MONGO_DATABASE_HOST']}:{$_ENV['MONGO_DATABASE_PORT']}");
      $this->con = $con->selectDatabase($this->dataBase);
    }
    return $this->con;
  }
}