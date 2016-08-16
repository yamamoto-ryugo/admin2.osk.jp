<?php

/*

  config.php
  設定ファイル

*/


/* DB接続情報（MySQL） */
// ローカル
$host = 'localhost';
$dbname = 'osk';
$user = 'root';
$password = 'ryugo1120';

$dbh = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $user, $password);

define('PASSWORD_KEY', 'EO&(ZOM&'); // 暗号化キー（8桁のパスワード）

error_reporting(E_ALL & ~E_NOTICE);
