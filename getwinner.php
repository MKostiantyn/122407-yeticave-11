<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('categories.php');
require_once "vendor/autoload.php";

$winners_query = <<<SQL
    SELECT
        l.id as lot_id,
        l.name as lot_name,
        image_url,
        l.date_end as date_end,
        bb.total as total,
        bb.author_id as author_id,
        u.name as user_name,
        u.email as email,
        u.id as winner_id
    FROM lots l
    JOIN (
        SELECT b.lot_id, b.total, b.author_id
        FROM bets b
        INNER JOIN (
            SELECT lot_id, MAX(total) total
            FROM bets
            GROUP BY lot_id
        ) AS bets_max ON b.lot_id = bets_max.lot_id AND b.total = bets_max.total
    ) AS bb ON bb.lot_id = l.id
    JOIN users u ON bb.author_id = u.id
    WHERE l.winner_id IS NULL AND DATE(l.date_end) <= DATE(NOW())
SQL;

$winners_result = runQuery($link, $winners_query);
$winners = mysqli_fetch_all($winners_result, MYSQLI_ASSOC);
if ($winners && count($winners)) {
    foreach ($winners as $winner) {
        $update_query = <<<SQL
    UPDATE lots SET winner_id = ? WHERE id = ?
SQL;
        $update_result = runQuery($link, $update_query, [
            $winner['author_id'],
            $winner['lot_id']
        ]);
        $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
        $transport->setUsername("keks@phpdemo.ru");
        $transport->setPassword("htmlacademy");
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
        $message->setBcc($winner["email"]);
        $message_content = include_template('email.php', ['winner' => $winner]);
        $message->setBody($message_content, 'text/html');
        $result = $mailer->send($message);
    }
}

