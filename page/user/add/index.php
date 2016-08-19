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

  function user_idExists($user_id, $dbh) {
    $sql = "SELECT * FROM user WHERE user_id = :user_id LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":user_id" => $user_id));
    $user = $stmt->fetch();
    return $user ? true : false;
  }

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CSRF対策
    setToken();
  } else {
    checkToken();

    $name_1 = $_POST['name_1'];
    $name_2 = $_POST['name_2'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $admin = (int) $_POST['admin'];
    $party = $_POST['party'];

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

    // ユーザーID使用済み
    if (user_idExists($user_id, $dbh)) {
      $err['user_id'] = '入力頂いたユーザーIDは既に使用されています。';
    }

    // パスワードが空
    if ($password == '') {
      $err['password'] = 'パスワードを入力してください。';
    }

    // 管理者権限が空
    if ($admin == '') {
      $err['admin'] = '管理者権限を選択してください。';
    }

    if (empty($err)) {
      // 登録処理

      $sql = "INSERT INTO user
             (name_1, name_2, user_id, password, admin, party, created)
             VALUES
             (:name_1, :name_2, :user_id, :password, :admin, :party, now())";
      $stmt = $dbh->prepare($sql);
      $params = array(
        ":name_1" => $name_1,
        ":name_2" => $name_2,
        ":user_id" => $user_id,
        ":password" => getSha1Password($password),
        ":admin" => $admin,
        ":party" => $party
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

  </head>
  <body class="skin-blue">
    <div class="wrapper">
      <?php require_once('../../../setting/header.php'); ?>

      <?php require_once('../../../setting/sidebar.php'); ?>

      <div class="content-wrapper">
        <section class="content-header">
          <h1>ユーザー登録</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li><a href="/page/user/">ユーザー設定</a></li>
            <li>ユーザー登録</li>
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
              <h3 class="box-title">基本情報</h3>
            </div>

            <div class="box-body">
              <form action="" method="post">
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">氏名</div>
                    <div class="col-xs-3">
                      <input type="text" class="form-control" name="name_1" placeholder="氏" value="<?php echo h($name_1); ?>">
                    </div>
                    <div class="col-xs-3">
                      <input type="text" class="form-control" name="name_2" placeholder="名" value="<?php echo h($name_2); ?>">
                    </div>
                    <div class="col-xs-3"></div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">ユーザーID</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="user_id" placeholder="ユーザーID" value="<?php echo h($user_id); ?>">
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
                      <select class="form-control" name="admin">
                        <option value="">選択してください。</option>
                        <option value="1">なし</option>
                        <option value="2">あり</option>
                      </select>
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">所属事業所</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="party" placeholder="所属事業所" value="<?php echo h($party); ?>">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-4">
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
