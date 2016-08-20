<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">機能一覧</li>

      <li><a href="/">TOP</a></li>

      <?php if ($me['admin'] == 2) : ?>
        <li><a href="/page/user/">ユーザー設定</a></li>

      <?php endif; ?>

      <li><a href="/page/profile/">プロフィール設定</a></li>
      <li><a href="">団体設定</a></li>

      <?php if ($me['admin'] == 2) : ?>
        <li><a href="">よくある質問設定</a></li>
        <li><a href="">お問い合わせ一覧</a></li>
        <li><a href="">システム設定</a></li>
      <?php endif; ?>
    </ul>
  </section>
</aside>
