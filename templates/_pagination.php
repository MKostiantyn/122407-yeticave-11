<?php if ($pages && count($pages) > 1): ?>
<ul class="pagination-list">
    <?php if ($current_page != 1): ?>
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
    <?php endif; ?>
    <?php foreach ($pages as $page): ?>
        <li class="pagination-item<?= $current_page == $page ? ' pagination-item-active' : ''; ?>">
            <a <?= $current_page == $page ? '' : 'href="/search.php?search=' . $_GET['search'] . '&page=' . $page . '"'; ?>><?= $page; ?></a>
        </li>
    <?php endforeach; ?>
    <?php if ($current_page != count($pages)): ?>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>
