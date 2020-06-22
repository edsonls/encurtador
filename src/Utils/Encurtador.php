<?php


namespace App\Utils;


abstract class Encurtador
{
  protected static string $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";

  public static function generateRandomString($length = 6): string
  {
    $sets = explode('|', self::$chars);
    $all = '';
    $randString = '';
    foreach ($sets as $set) {
      $randString .= $set[array_rand(str_split($set))];
      $all .= $set;
    }
    $all = str_split($all);
    for ($i = 0; $i < $length - count($sets); $i++) {
      $randString .= $all[array_rand($all)];
    }
    $randString = str_shuffle($randString);
    return $randString;
  }
}