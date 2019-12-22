<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if($bets && count($bets)): ?>
        <table class="rates__list">
    <?php foreach($bets as $bet): ?>
        <?php
        $date_difference = getDateDifference($bet['date_end']);
        $rates_item_class = $bet['winner_id'] == $_SESSION['user_id'] ? ' rates__item--win' : $date_difference > 0 ? '' : ' rates__item--end';
        ?>
        <tr class="rates__item<?= $rates_item_class ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?= $bet['image_url'] ?>" width="54" height="40" alt="<?= $bet['lot_name'] ?>">
                </div>
                <h3 class="rates__title"><a href="<?= '/lot.php?id=' . $bet['lot_id'] ?>"><?= $bet['lot_name'] ?></a></h3>
            </td>
            <td class="rates__category">
                <?= $bet['category_name'] ?>
            </td>
            <td class="rates__timer">
                <?php if($bet['winner_id'] == $_SESSION['user_id']): ?>
                <div class="timer timer--win">Ставка выиграла</div>
                <?php elseif($date_difference > 0): ?>
                    <div class="timer<?= $date_difference > 60 * 60 ? '' : ' timer--finishing'; ?>"><?= implode(':', formatTime($date_difference)); ?></div>
                <?php else: ?>
                    <div class="timer timer--end">Торги окончены</div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= formatPrice($bet['total']); ?>
            </td>
            <td class="rates__time">
                <?= formatTimeToStringFormat($bet['date_bet']); ?>
            </td>
        </tr>
    <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have not placed any bet!</p>
    <?php endif; ?>
</section>
