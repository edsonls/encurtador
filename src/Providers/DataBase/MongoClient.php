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
      $con = new Mongo("mongodb://172.17.0.4:27017");
      $this->con = $con->selectDatabase($this->dataBase);
    }
    return $this->con;
  }
}