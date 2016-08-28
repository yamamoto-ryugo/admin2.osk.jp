<?php

  require_once('../../../setting/config.php');
  require_once('../../../setting/functions.php');
  require_once('../../../setting/version.php');

  session_start();

  if (empty($_SESSION['me'])) {
    header('Location: ../../../page/login/');
    exit;
  }

  if ($me['admin'] == 1) {
    header('Location: ../../../');
    exit;
  }

  $me = $_SESSION['me'];

  $id = $_GET['id'];

  $sql = "SELECT * FROM news WHERE id = ?";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(1, $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CSRF対策
    setToken();
  } else {
    checkToken();

    $title = $_POST['title'];
    $news = $_POST['news'];
    $modified_staff = $_POST['modified_staff'];

    $err = array();

    // 質問が空
    if ($title == '') {
      $err['title'] = 'タイトルを入力してください。';
    }

    // 回答が空
    if ($news == '') {
      $err['news'] = '質問を入力してください。';
    }

    if (empty($err)) {
      // 登録処理

      $sql = "UPDATE news SET title = ?, news = ?, modified = now(), modified_staff = ? WHERE id = ?";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $title, PDO::PARAM_STR);
      $stmt->bindValue(2, $news, PDO::PARAM_STR);
      $stmt->bindValue(3, $modified_staff, PDO::PARAM_STR);
      $stmt->bindValue(4, $id, PDO::PARAM_STR);

      $stmt->execute();

      $dbh = null;

      header("Location: ../");
      exit;
    }

  }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>

    <?php require_once('../../../setting/head.php'); ?>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

  </head>
  <body class="skin-blue">
    <div class="wrapper">
      <?php require_once('../../../setting/header.php'); ?>

      <?php require_once('../../../setting/sidebar.php'); ?>

      <div class="content-wrapper">
        <section class="content-header">
          <h1>お知らせ編集</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li><a href="/page/news/">お知らせ設定</a></li>
            <li>お知らせ編集</li>
          </ol>
        </section>

        <section class="content">

          <!-- エラー表示 -->
          <?php if(!empty($err['question'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['question']; ?>
            </div>
          <?php elseif (!empty($err['answer'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['answer']; ?>
            </div>
          <?php endif; ?>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">基本情報</h3>
            </div>

            <div class="box-body">
              <form action="" method="post">
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">タイトル</div>
                    <div class="col-xs-9">
                      <input type="text" name="title" class="form-control" value="<?php echo $result['title']; ?>">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">お知らせ</div>
                    <div class="col-xs-9">
                      <textarea class="form-control" name="news" rows="5"><?php echo h($result['news']); ?></textarea>
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-4">
                      <input type="hidden" name="modified_staff" value="<?php echo h($me['name_1'] . " " . $me['name_2']); ?>">
                      <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                    </div>
                    <div class="col-xs-4">
                      <input type="submit" class="btn btn-primary" value="上記の内容で登録する">
                    </div>
                    <div class="col-xs-4"></div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div>

      <?php require_once('../../../setting/footer.php'); ?>

    </div>
    <?php require_once('../../../setting/script.php'); ?>
  </body>
</html>
