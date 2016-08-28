<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">機能一覧</li>

      <li><a href="/">TOP</a></li>

      <?php if ($me['admin'] == 2) : ?>
        <li><a href="/page/user/">ユーザー設定</a></li>

      <?php endif; ?>

      <li><a href="/page/profile/">プロフィール設定</a></li>
      <li><a href="/page/party/">団体設定</a></li>

      <?php if ($me['admin'] == 2) : ?>
        <li><a href="/page/faq/">よくある質問設定</a></li>
        <li><a href="">お知らせ設定</a></li>
        <li><a href="">システム設定</a></li>
        <li><a href="">各ページ設定</a></li>
      <?php endif; ?>
      <li><a href="/page/logout/">ログアウト</a></li>
    </ul>
  </section>
</aside>
