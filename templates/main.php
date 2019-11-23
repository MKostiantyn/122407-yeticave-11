<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <?php if ($categories): ?>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item <?= 'promo__item--' . htmlspecialchars($category['code']); ?>">
                <a class="promo__link" href="pages/all-lots.html"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php if ($announcements): ?>
            <?php foreach ($announcements as $announcement): ?>
            <?php $date_range = getDateRange($announcement['date_end']); ?>
            <?php if (count($date_range)): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $announcement['image_url']; ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($announcement['category']); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($announcement['name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= formatPrice($announcement['price_default']); ?></span>
                            </div>
                            <div class="lot__timer timer<?= intval($date_range[0]) > 0 ? '' : ' timer--finishing'; ?>"><?= implode(':', $date_range); ?></div>
                        </div>
                    </div>
                </li>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>
