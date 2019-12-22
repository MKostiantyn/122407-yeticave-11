<section class="lot-item container">
    <h2><?= escapeString($lot['name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= isset($lot['image_url']) ? escapeString($lot['image_url']) : ''; ?>" width="730" height="548" alt="<?= escapeString($lot['name']); ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= escapeString($lot['category_name']); ?></span></p>
            <p class="lot-item__description"><?= escapeString($lot['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($is_auth): ?>
            <div class="lot-item__state">
                <?php $date_range = getDateRange($lot['date_end']); ?>
                <div class="lot-item__timer timer<?= intval($date_range[0]) > 0 ? '' : ' timer--finishing'; ?>"><?= implode(':', $date_range); ?></div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= formatPrice($lot['price_default']); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= formatPrice($lot['price_step']); ?></span>
                    </div>
                </div>
                <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="12 000">
                        <span class="form__error">Введите наименование лота</span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php endif; ?>
            <?php if ($bets && $bets_count = count($bets)): ?>
            <div class="history">
                <h3>История ставок (<span><?= $bets_count; ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets as $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?= escapeString($bet['name']); ?></td>
                            <td class="history__price"><?= formatPrice($bet['total']); ?></td>
                            <td class="history__time"><?= $bet['date_bet']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php endif; ?>
        </div>
</section>
