<?php

  require_once('setting/config.php');
  require_once('setting/functions.php');
  require_once('setting/version.php');

  session_start();

  if (empty($_SESSION['me'])) {
    header('Location: page/login/');
    exit;
  }

  $me = $_SESSION['me'];

  define('PER_PAGE', 5);

  $page = 1;

  $offset = PER_PAGE * ($page - 1);

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <?php require_once('setting/head.php'); ?>
  </head>
  <body class="skin-blue">
    <div class="wrapper">
      <?php require_once('setting/header.php'); ?>

      <?php require_once('setting/sidebar.php'); ?>

      <div class="content-wrapper">
        <section class="content-header">
          <h1>岡山県就労移行支援事業所協議会</h1>
          <ol class="breadcrumb">
            <li>TOP</li>
          </ol>
        </section>

        <section class="content">

          <?php if ($me[admin] == 2) : ?>
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">ユーザー一覧</h3>
              </div>

              <div class="box-body">
                <a class="btn btn-primary" style="width: 200px;" href="/page/user/add/">新規登録</a>
                <a class="btn btn-primary" style="width: 200px;" href="/page/user/">一覧</a>

                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th nowrap>ID</th>
                          <th nowrap>氏</th>
                          <th nowrap>名</th>
                          <th nowrap>ユーザーID</th>
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

                        $i = 1;

                      ?>
                      <tbody>
                        <?php foreach ($users as $user) : ?>
                          <tr class="odd gradeX">
                            <td nowrap><?php echo $i++; ?></td>
                            <td nowrap><?php echo $user['name_1']; ?></td>
                            <td nowrap><?php echo $user['name_2']; ?></td>
                            <td nowrap><?php echo $user['user_id']; ?></td>
                            <td nowrap>
                              <?php if ($user['admin'] == 1) : ?>
                                なし
                              <?php else: ?>
                                あり
                              <?php endif; ?>
                            </td>
                            <td nowrap><?php echo $user['party']; ?></td>
                            <td nowrap><?php echo $user['created']; ?></td>
                            <td nowrap><?php echo $user['created_staff']; ?></td>
                            <td nowrap><?php echo $user['modified']; ?></td>
                            <td nowrap><?php echo $user['modified_staff']; ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>



          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">団体一覧</h3>
            </div>

            <div class="box-body">

            </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">よくある質問一覧</h3>
            </div>

            <div class="box-body">

            </div>
          </div>

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">お問い合わせ一覧</h3>
            </div>

            <div class="box-body">

            </div>
          </div>
        </section>
      </div>

      <?php require_once('setting/footer.php'); ?>

    </div>
    <?php require_once('setting/script.php'); ?>
    </div>
  </body>
</html>
