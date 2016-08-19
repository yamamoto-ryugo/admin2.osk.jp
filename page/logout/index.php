<?php

require_once('../../setting/config.php');
require_once('../../setting/functions.php');

session_start();

$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time()-86400, '../../');
}

session_destroy();

header('Location: ../login/');
