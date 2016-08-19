<header class="main-header">
  <a href="/" class="logo">管理画面</a>

  <nav class="navbar navbar-static-top" role="navigation">
    <a href="" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <ul class="nav navbar-nav">
      <li><a href=""><?php echo $ver; ?></a></li>
    </ul>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li>
          <a href="/page/logout/">
            <i class="fa fa-sign-out"></i>ログアウト（<?php echo $me['name_1'] . " " . $me['name_2']; ?>）
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>
