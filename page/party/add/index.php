<?php

  require_once('../../../setting/config.php');
  require_once('../../../setting/functions.php');
  require_once('../../../setting/version.php');

  session_start();

  if (empty($_SESSION['me'])) {
    header('Location: ../../../page/login/');
    exit;
  }

  function party_idExists($party_id, $dbh) {
    $sql = "SELECT * FROM party WHERE party_id = :party_id LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":party_id" => $party_id));
    $party = $stmt->fetch();
    return $party ? true : false;
  }

  $me = $_SESSION['me'];

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CSRF対策
    setToken();
  } else {
    checkToken();

    $party_id = $_POST['party_id'];
    $party_name = $_POST['party_name'];
    $zip31 = $_POST['zip31'];
    $zip32 = $_POST['zip32'];
    $pref31 = $_POST['pref31'];
    $addr31 = $_POST['addr31'];
    $address = $_POST['address'];
    $tell_1 = $_POST['tell_1'];
    $tell_2 = $_POST['tell_2'];
    $tell_3 = $_POST['tell_3'];
    $service = $_POST['service'];
    $special = $_POST['special'];
    $url = $_POST['url'];
    $created_staff = $_POST['created_staff'];

    $err = array();

    // 事業所番号が空
    if ($party_id == '') {
      $err['party_id'] = '事業所番号を入力してください。';
    }

    // 事業所番号が登録済み
    if (party_idExists($party_id, $dbh)) {
      $err['party_id'] = '入力頂いた事業所番号は既に使用されています。';
    }

    // 事業所名が空
    if ($party_name == '') {
      $err['party_name'] = '事業所名を入力してください。';
    }

    // 郵便番号が空
    if ($zip31 == '') {
      $err['zip'] = '郵便番号を入力してください。';
    }
    if ($zip32 == '') {
      $err['zip'] = '郵便番号を入力してください。';
    }

    // 都道府県が空
    if ($pref31 == '') {
      $err['pref31'] = '都道府県を入力してください。';
    }

    // 市区町村が空
    if ($addr31 == '') {
      $err['addr31'] = '市区町村を入力してください。';
    }

    // 市区町村以下が空
    if ($address == '') {
      $err['address'] = '市区町村以下を入力してください。';
    }

    // 電話番号が空
    if ($tell_1 == '') {
      $err['tell'] = '電話番号を入力してください。';
    }
    if ($tell_2 == '') {
      $err['tell'] = '電話番号を入力してください。';
    }
    if ($tell_3 == '') {
      $err['tell'] = '電話番号を入力してください。';
    }

    // 支援の特徴が空
    if ($special == '') {
      $err['special'] = '支援の特徴を入力してください。';
    }

    if (empty($err)) {
      // 登録処理

      $sql = "INSERT INTO party
             (party_id, party_name, zip31, zip32, pref31, addr31, address, tell_1, tell_2, tell_3, service, special, url, created, created_staff)
             VALUES
             (:party_id, :party_name, :zip31, :zip32, :pref31, :addr31, :address, :tell_1, :tell_2, :tell_3, :service, :special, :url, now(), :created_staff)";
      $stmt = $dbh->prepare($sql);
      $params = array(
        ":party_id" => $party_id,
        ":party_name" => $party_name,
        ":zip31" => $zip31,
        ":zip32" => $zip32,
        ":pref31" => $pref31,
        ":addr31" => $addr31,
        ":address" => $address,
        ":tell_1" => $tell_1,
        ":tell_2" => $tell_2,
        ":tell_3" => $tell_3,
        ":service" => $service,
        ":special" => $special,
        ":url" => $url,
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
          <h1>事業所登録</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li><a href="/page/party/">団体設定</a></li>
            <li>事業所登録</li>
          </ol>
        </section>

        <section class="content">

          <!-- エラー表示 -->
          <?php if(!empty($err['party_id'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['party_id']; ?>
            </div>
          <?php elseif (!empty($err['party_name'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['party_name']; ?>
            </div>
          <?php elseif (!empty($err['zip'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['zip']; ?>
            </div>
          <?php elseif (!empty($err['pref31'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['pref31']; ?>
            </div>
          <?php elseif (!empty($err['addr31'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['addr31']; ?>
            </div>
          <?php elseif (!empty($err['address'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['address']; ?>
            </div>
          <?php elseif (!empty($err['tell'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['tell']; ?>
            </div>
          <?php elseif (!empty($err['special'])) : ?>
            <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>エラー！</strong><?php echo $err['special']; ?>
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
                    <div class="col-xs-3">事業所番号</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="party_id" placeholder="事業所番号" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">事業所名</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="party_name" placeholder="事業所名" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">郵便番号</div>
                    <div class="col-xs-3">
                      <input type="text" class="form-control" name="zip31" placeholder="" value="">
                    </div>
                    <div class="col-xs-1">―</div>
                    <div class="col-xs-3">
                      <input type="text" class="form-control" name="zip32" placeholder="" value="" onKeyUp="AjaxZip3.zip2addr('zip31','zip32','pref31','addr31','addr31');">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">都道府県</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="pref31" placeholder="都道府県" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">市区町村</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="addr31" placeholder="市区町村" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">市区町村以下</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="address" placeholder="市区町村以下" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">電話番号</div>
                    <div class="col-xs-2">
                      <input type="text" class="form-control" name="tell_1" placeholder="" value="">
                    </div>
                    <div class="col-xs-1">―</div>
                    <div class="col-xs-2">
                      <input type="text" class="form-control" name="tell_2" placeholder="" value="">
                    </div>
                    <div class="col-xs-1">―</div>
                    <div class="col-xs-2">
                      <input type="text" class="form-control" name="tell_3" placeholder="" value="">
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">併設サービス</div>
                    <div class="col-xs-9">
                      <textarea class="form-control" name="service" rows="5"></textarea>
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">支援の特徴</div>
                    <div class="col-xs-9">
                      <textarea class="form-control" name="special" rows="5"></textarea>
                    </div>
                  </div>
                </div>
                <br />
                <div class="row">
                  <div class="form-group">
                    <div class="col-xs-3">ホームページアドレス</div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="url" placeholder="URL" value="">
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
