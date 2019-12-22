<?php $date_difference = getDateDifference($lot['date_end']); ?>
<div class="lot__image">
    <img src="<?= $lot['image_url'] ?>" width="350" height="260" alt="<?= escapeString($lot['name']); ?>">
</div>
<div class="lot__info">
    <span class="lot__category"><?= escapeString($lot['category_name']) ?></span>
    <h3 class="lot__title"><a class="text-link" href="<?= 'lot.php?id=' . $lot['id'] ?>"><?= escapeString($lot['name']); ?></a></h3>
    <div class="lot__state">
        <div class="lot__rate">
            <span class="lot__amount">Стартовая цена</span>
            <span class="lot__cost"><?= formatPrice($lot['price_default']); ?></span>
        </div>
        <div class="lot__timer timer<?= $date_difference > 60 * 60 ? '' : ' timer--finishing'; ?>"><?= implode(':', formatTime($date_difference)); ?></div>
    </div>
</div>
