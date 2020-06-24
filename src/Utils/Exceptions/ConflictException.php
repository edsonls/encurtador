<?php


namespace App\Utils\Exceptions;


use Exception;

class ConflictException extends Exception
{
  public function __construct($message = "")
  {
    parent::__construct($message);
  }
}