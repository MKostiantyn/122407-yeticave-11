<div class="container">
  <section class="lots">
      <h2>Результаты поиска по запросу «<span><?= escapeString($search_text); ?></span>»</h2>
      <?php if ($lots && count($lots)): ?>
          <ul class="lots__list">
          <?php foreach ($lots as $lot): ?>
              <li class="lots__item lot">
                  <?php echo include_template('_lot-item.php', ['lot' => $lot]); ?>
              </li>
          <?php endforeach; ?>
          </ul>
      <?php else: ?>
        <p>Ничего не найдено по вашему запросу</p>
      <?php endif; ?>
  </section>
    <?php echo include_template('_pagination.php', [
        'pages' => $pages,
        'current_page' => $current_page
    ]); ?>
</div>
