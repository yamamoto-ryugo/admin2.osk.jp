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

  define('PER_PAGE', 5);

  if (empty($_GET['page'])) {
    $page = 1;
  } else {
    $page = (int) $_GET['page'];
  }

  $offset = PER_PAGE * ($page - 1);

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
          <h1>団体設定</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li>団体設定</li>
          </ol>
        </section>

        <section class="content">

          <a class="btn btn-primary" style="width: 200px;" href="/page/party/add/">新規登録</a>

          <hr>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">団体一覧</h3>
              </div>

              <div class="box-body">

                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th nowrap>処理</th>
                          <th nowrap>ID</th>
                          <th nowrap>事業所番号</th>
                          <th nowrap>事業所名</th>
                          <th nowrap>郵便番号</th>
                          <th nowrap>住所</th>
                          <th nowrap>電話番号</th>
                          <th nowrap>併設サービス</th>
                          <th nowrap>支援の特徴</th>
                          <th nowrap>URL</th>
                          <th nowrap>登録日</th>
                          <th nowrap>登録スタッフ</th>
                          <th nowrap>更新日</th>
                          <th nowrap>更新スタッフ</th>
                        </tr>
                      </thead>
                      <?php

                        $sql = "SELECT * FROM party LIMIT " . $offset . "," . PER_PAGE;
                        $partys = array();
                        foreach ($dbh->query($sql) as $row) {
                          array_push($partys, $row);
                        }

                        $total = $dbh->query("SELECT COUNT(*) FROM party")->fetchColumn();
                        $totalPage = ceil($total / PER_PAGE);

                        $i = 1;

                      ?>
                      <tbody>
                        <?php foreach ($partys as $party) : ?>
                          <tr class="odd gradeX">
                            <td nowrap>
                              <a href="edit/?id=<?php echo $party['id']; ?>" class="btn btn-default"><i class="fa fa-cog"></i></a>
                              <?php if ($me['admin'] == 2) : ?>
                                <a href="delete/?id=<?php echo $party['id']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                              <?php endif; ?>
                            </td>
                            <td nowrap><?php echo $i++; ?></td>
                            <td nowrap><?php echo $party['party_id']; ?></td>
                            <td nowrap><?php echo $party['party_name']; ?></td>
                            <td nowrap><?php echo $party['zip31'] . "-" . $party['zip32']; ?></td>
                            <td nowrap><?php echo $party['pref31'] . $party['addr31'] . $party['address']; ?></td>
                            <td nowrap><a href="tel:<?php echo $party['tell_1'] . $party['tell_2'] . $party['tell_3']; ?>"><?php echo $party['tell_1'] . "-" . $party['tell_2'] . "-" . $party['tell_3']; ?></a></td>
                            <td nowrap><?php echo nl2br($party['service']); ?></td>
                            <td nowrap><?php echo nl2br($party['special']); ?></td>
                            <td nowrap><a href="<?php echo $party['url']; ?>" target="_blank"><?php echo $party['url']; ?></a></td>
                            <td nowrap><?php echo $party['created']; ?></td>
                            <td nowrap><?php echo $party['created_staff']; ?></td>
                            <td nowrap><?php echo $party['modified']; ?></td>
                            <td nowrap><?php echo $party['modified_staff']; ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <?php if ($page > 1) : ?>
              <a href="?page=<?php echo $page - 1; ?>" class="btn btn-default">前</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
              <?php if ($page == $i) : ?>
                <a href="?page=<?php echo $i; ?>" class="btn btn-primary"><?php echo $i; ?></a>
              <?php else: ?>
                <a href="?page=<?php echo $i; ?>" class="btn btn-default"><?php echo $i; ?></a>
              <?php endif; ?>

            <?php endfor; ?>
            <?php if ($page < $totalPage) : ?>
              <a href="?page=<?php echo $page + 1; ?>" class="btn btn-default">次</a>
            <?php endif; ?>
        </section>
      </div>

      <?php require_once('../../setting/footer.php'); ?>

    </div>
    <?php require_once('../../setting/script.php'); ?>
    </div>
  </body>
</html>
