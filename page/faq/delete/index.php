<?php
require_once('../../../setting/config.php');
require_once('../../../setting/functions.php');
require_once('../../../setting/version.php');

session_start();

if (empty($_SESSION['me'])) {
  header('Location: ../../../page/login/');
  exit;
}

$me = $_SESSION['me'];

if ($me['admin'] == 1) {
  header('Location: ../../../');
  exit;
}

$id = (int) $_GET['id'];

$sql = "DELETE FROM faq WHERE id = ?";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $id, PDO::PARAM_INT);
$stmt->execute();

$dbh = null;

header("Location: ../");
exit;
