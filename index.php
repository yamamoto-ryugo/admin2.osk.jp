<?php

  require_once('setting/config.php');
  require_once('setting/functions.php');

  session_start();

  if (empty($_SESSION['me'])) {
    header('Location: page/login/');
    exit;
  }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
  </body>
</html>
