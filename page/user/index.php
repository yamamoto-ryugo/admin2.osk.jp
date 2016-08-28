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

  if ($me['admin'] == 1) {
    header('Location: ../../');
    exit;
  }

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
          <h1>ユーザー設定</h1>
          <ol class="breadcrumb">
            <li><a href="/">TOP</a></li>
            <li>ユーザー設定</li>
          </ol>
        </section>

        <section class="content">

          <a class="btn btn-primary" style="width: 200px;" href="/page/user/add/">新規登録</a>

          <hr>

            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">ユーザー一覧</h3>
              </div>

              <div class="box-body">

                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th nowrap>処理</th>
                          <th nowrap>ID</th>
                          <th nowrap>氏</th>
                          <th nowrap>名</th>
                          <th nowrap>ユーザーID</th>
                          <th nowrap>メールアドレス</th>
                          <th nowrap>管理者権限</th>
                          <th nowrap>所属団体</th>
                          <th nowrap>登録日</th>
                          <th nowrap>登録スタッフ</th>
                          <th nowrap>更新日</th>
                          <th nowrap>更新スタッフ</th>
                        </tr>
                      </thead>
                      <?php

                        $sql = "SELECT * FROM user LIMIT " . $offset . "," . PER_PAGE;
                        $users = array();
                        foreach ($dbh->query($sql) as $row) {
                          array_push($users, $row);
                        }

                        $total = $dbh->query("SELECT COUNT(*) FROM user")->fetchColumn();
                        $totalPage = ceil($total / PER_PAGE);

                        $i = 1;

                      ?>
                      <tbody>
                        <?php foreach ($users as $user) : ?>
                          <tr class="odd gradeX">
                            <td nowrap>
                              <a href="edit/?id=<?php echo $user['id']; ?>" class="btn btn-default"><i class="fa fa-cog"></i></a>
                              <a href="delete/?id=<?php echo $user['id']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                            <td nowrap><?php echo $i++; ?></td>
                            <td nowrap><?php echo $user['name_1']; ?></td>
                            <td nowrap><?php echo $user['name_2']; ?></td>
                            <td nowrap><?php echo $user['user_id']; ?></td>
                            <td nowrap><?php echo $user['mail']; ?></td>
                            <td nowrap>
                              <?php if ($user['admin'] == 1) : ?>
                                なし
                              <?php else: ?>
                                あり
                              <?php endif; ?>
                            </td>
                            <td nowrap><?php echo $user['party']; ?></td>
                            <td nowrap>
                              <?php

                                $created = $user['created'];

                                echo date('Y/m/d H:i', strtotime($created));

                              ?>
                            </td>
                            <td nowrap>
                              <?php echo $user['created_staff']; ?>
                            </td>
                            <td nowrap>
                              <?php
                                if ($user['modified'] == 0) {
                                  echo "-";
                                } else {
                                  $modified = $user['modified'];
                                  echo date('Y/m/d H:i', strtotime($modified));
                                }
                              ?>
                            </td>
                            <td nowrap>
                              <?php
                                if (empty($user['modified_staff'])) {
                                  echo "-";
                                } else {
                                  echo $user['modified_staff'];
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
