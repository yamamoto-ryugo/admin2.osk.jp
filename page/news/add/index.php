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

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CSRF対策
    setToken();
  } else {
    checkToken();

    $title = $_POST['title'];
    $news = $_POST['news'];
    $created_staff = $_POST['created_staff'];

    $err = array();

    // タイトルが空
    if ($title == '') {
      $err['title'] = 'タイトルを入力してください。';
    }

    // お知らせが空
    if ($news == '') {
      $err['news'] = 'お知らせを入力してください。';
    }

    if (empty($err)) {
      // 登録処理

      $sql = "INSERT INTO news
             (title, news, created, created_staff)
             VALUES
             (:title, :news, now(), :created_staff)";
      $stmt = $dbh->prepare($sql);
      $params = array(
        ":title" => $title,
        ":news" => $news,
        ":created_staff" => $created_staff
      );
      $stmt->execute($params);
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
          <h1>お知らせ登録</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li><a href="/page/news/">お知らせ設定</a></li>
            <li>お知らせ登録</li>
          </ol>
        </section>

        <section class="content">

          <!-- エラー表示 -->
          <?php if(!empty($err['title'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['title']; ?>
            </div>
          <?php elseif (!empty($err['news'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['news']; ?>
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
                      <input type="text" class="form-control" name="title" placeholder="タイトル" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">お知らせ</div>
                    <div class="col-xs-9">
                      <textarea class="form-control" name="news" rows="5"></textarea>
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-4">
                      <input type="hidden" name="created_staff" value="<?php echo h($me['name_1'] . " " . $me['name_2']); ?>">
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
