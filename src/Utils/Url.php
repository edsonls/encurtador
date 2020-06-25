<?php


namespace App\Utils;


class Url
{
  public static function formatUrl(string $shortUrl): string
  {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/{$shortUrl}";
  }
}