<?php

class Database
{
  public static function getConnection(): PDO
  {
    static $pdo = null;

    if ($pdo === null) {
      try {
        $pdo = new PDO(
          'mysql:host=localhost;dbname=my_shop;charset=utf8',
          'root',
          'Ser1970geI*'
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
      }
    }

    return $pdo;
  }
}