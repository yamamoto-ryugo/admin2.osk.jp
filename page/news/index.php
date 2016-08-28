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

<?php

  $sql = "SELECT * FROM news LIMIT " . $offset . "," . PER_PAGE;
  $news = array();
  foreach ($dbh->query($sql) as $row) {
    array_push($news, $row);
  }

  $total = $dbh->query("SELECT COUNT(*) FROM news")->fetchColumn();
  $totalPage = ceil($total / PER_PAGE);

  $i = 1;

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
          <h1>
            <?php if ($me['admin'] == 1) : ?>
              お知らせ
            <?php else: ?>
              お知らせ設定
            <?php endif; ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li>
              <?php if ($me['admin'] == 1) : ?>
                お知らせ
              <?php else: ?>
                お知らせ設定
              <?php endif; ?>
            </li>
          </ol>
        </section>

        <section class="content">
          <?php if ($me['admin'] == 2): ?>
            <a class="btn btn-primary" style="width: 200px;" href="/page/news/add/">新規登録</a>

            <hr>
          <?php endif; ?>
          <?php if ($me['admin'] == 1) : ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">お知らせ</h3>
              </div>

              <div class="box-body">
                <ul class="products-list product-list-in-box">

                  <?php foreach ($news as $new) : ?>
                    <li class="item">
                      <div class="product-img">
                        <img src="/dist/img/default-50x50.gif">
                      </div>

                      <div class="product-info">
                        <a href="" class="product-title">
                          <?php echo $new['title']; ?>
                        </a>
                        <div class="product-description">
                          <?php echo nl2br($new['news']); ?><br /><br />
                          記事登録者：<?php echo $new['created_staff'] ?><br />
                          記事登録日：
                          <?php
                            $created = $new['created'];

                            echo date('Y/m/d H:i', strtotime($created));
                          ?>
                          <br />
                          <?php
                            if (!empty($news[modified_staff])) {
                              echo "最終更新者：";
                              echo $new['modified_staff'];
                              echo "<br />";
                              echo "最終更新日：";
                              $modified = $new['modified'];
                              echo date('Y/m/d H:i', strtotime($modified));
                            }
                          ?>
                        </div>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>

            <?php if ($me['admin'] == 2) : ?>
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">お知らせ一覧</h3>
                </div>

                <div class="box-body">

                  <div class="row">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover">
                        <thead>
                          <tr>
                            <th nowrap>処理</th>
                            <th nowrap>ID</th>
                            <th nowrap>タイトル</th>
                            <th nowrap>お知らせ</th>
                            <th nowrap>登録日</th>
                            <th nowrap>登録スタッフ</th>
                            <th nowrap>更新日</th>
                            <th nowrap>更新スタッフ</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php foreach ($news as $new) : ?>
                            <tr class="odd gradeX">
                              <td nowrap>
                                <a href="edit/?id=<?php echo $new['id']; ?>" class="btn btn-default"><i class="fa fa-cog"></i></a>
                                <?php if ($me['admin'] == 2) : ?>
                                  <a href="delete/?id=<?php echo $new['id']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                              </td>
                              <td nowrap><?php echo $i++; ?></td>
                              <td nowrap><?php echo $new['title']; ?></td>
                              <td nowrap><?php echo nl2br($new['news']); ?></td>
                              <td nowrap>
                                <?php
                                  $created = $new['created'];

                                  echo date('Y/m/d H:i', strtotime($created));
                                ?>
                              </td>
                              <td nowrap><?php echo $new['created_staff']; ?></td>
                              <td nowrap>
                                <?php
                                  if ($new['modified'] == 0) {
                                    echo "-";
                                  } else {
                                    $modified = $new['modified'];
                                    echo date('Y/m/d H:i', strtotime($modified));
                                  }
                                ?>
                              </td>
                              <td nowrap>
                                <?php
                                  if (empty($new['modified_staff'])) {
                                    echo "-";
                                  } else {
                                    echo $new['modified_staff'];
                                  }
                                ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
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
