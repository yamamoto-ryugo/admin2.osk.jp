<?php

  require_once('../../setting/config.php');
  require_once('../../setting/functions.php');
  require_once('../../setting/version.php');

  session_start();

  if (empty($_SESSION['me'])) {
    header('Location: ../../page/login/');
    exit;
  }

  $me = $_SESSION['me'];

  $id = $me['id'];

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CSRF対策
    setToken();
  } else {
    checkToken();

    $name_1 = $_POST['name_1'];
    $name_2 = $_POST['name_2'];
    $user_id = $_POST['user_id'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $party = $_POST['party'];
    $modified_staff = $_POST['modified_staff'];

    $err = array();

    // 氏名が空
    if ($name_1 == '') {
      $err['name'] = '氏名を入力してください。';
    }
    if ($name_2 == '') {
      $err['name'] = '氏名を入力してください。';
    }

    // ユーザーIDが空
    if ($user_id == '') {
      $err['user_id'] = 'ユーザーIDを入力してください。';
    }

    // パスワードが空
    if ($password == '') {
      $err['password'] = 'パスワードを入力してください。';
    }

    if (empty($err)) {
      // 登録処理

      $sql = "UPDATE user SET name_1 = ?, name_2 = ?, user_id = ?, mail = ?, password = ?, party = ?, modified = now(), modified_staff = ? WHERE id = ?";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $name_1, PDO::PARAM_STR);
      $stmt->bindValue(2, $name_2, PDO::PARAM_STR);
      $stmt->bindValue(3, $user_id, PDO::PARAM_STR);
      $stmt->bindValue(4, $mail, PDO::PARAM_STR);
      $stmt->bindValue(5, getSha1Password($password), PDO::PARAM_STR);
      $stmt->bindValue(6, $party, PDO::PARAM_STR);
      $stmt->bindValue(7, $modified_staff, PDO::PARAM_STR);
      $stmt->bindValue(8, $id, PDO::PARAM_INT);

      $stmt->execute();

      $dbh = null;

      header("Location: ../../");
      exit;
    }

  }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <?php require_once('../../setting/head.php'); ?>
  </head>
  <body class="skin-blue">
    <div class="wrapper">
      <?php require_once('../../setting/header.php'); ?>

      <?php require_once('../../setting/sidebar.php'); ?>

      <div class="content-wrapper">
        <section class="content-header">
          <h1>プロフィール設定（<?php echo h($me['name_1'] . " " . $me['name_2']); ?>）</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li>プロフィール設定（<?php echo h($me['name_1'] . " " . $me['name_2']); ?>）</li>
          </ol>
        </section>

        <section class="content">

          <!-- エラー表示 -->
          <?php if(!empty($err['name'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['name']; ?>
            </div>
          <?php elseif (!empty($err['user_id'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['user_id']; ?>
            </div>
          <?php elseif (!empty($err['password'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['password']; ?>
            </div>
          <?php elseif (!empty($err['admin'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['admin']; ?>
            </div>
          <?php endif; ?>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">ユーザー情報</h3>
              </div>

              <div class="box-body">
                <form action="" method="post">
                  <div class="row">
                    <div class="form-group">
                      <div class="col-xs-3">氏名</div>
                      <div class="col-xs-3">
                        <input type="text" class="form-control" name="name_1" placeholder="氏" value="<?php echo h($me['name_1']); ?>">
                      </div>
                      <div class="col-xs-3">
                        <input type="text" class="form-control" name="name_2" placeholder="名" value="<?php echo h($me['name_2']); ?>">
                      </div>
                      <div class="col-xs-3"></div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <div class="col-xs-3">ユーザーID</div>
                      <div class="col-xs-9">
                        <input type="text" class="form-control" name="user_id" placeholder="ユーザーID" value="<?php echo h($me['user_id']); ?>">
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <div class="col-xs-3">メールアドレス</div>
                      <div class="col-xs-9">
                        <input type="text" class="form-control" name="mail" placeholder="" value="<?php echo h($me['mail']); ?>">
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <div class="col-xs-3">パスワード</div>
                      <div class="col-xs-9">
                        <input type="password" class="form-control" name="password" placeholder="パスワード" value="">
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <div class="col-xs-3">管理者権限</div>
                      <div class="col-xs-9">
                        <?php if ($me['admin'] == 1) : ?>
                          なし
                        <?php else: ?>
                          あり
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <br />
                  <div class="row">
                    <div class="form-group">
                      <div class="col-xs-3">所属事業所</div>
                      <div class="col-xs-9">
                        <input type="text" class="form-control" name="party" placeholder="所属事業所" value="<?php echo h($me['party']); ?>">
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

      <?php require_once('../../setting/footer.php'); ?>

    </div>
    <?php require_once('../../setting/script.php'); ?>
    </div>
  </body>
</html>
