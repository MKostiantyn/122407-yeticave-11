INSERT INTO categories
    (name, code)
VALUES
    (N'Доски и лыжи', 'boards'),
    (N'Крепления', 'attachment'),
    (N'Ботинки', 'boots'),
    (N'Одежда', 'clothing'),
    (N'Инструменты', 'tools'),
    (N'Разное', 'other');

INSERT INTO users
    (date_registration, email, name, password, contacts)
VALUES
    (CURRENT_TIMESTAMP, 'email@email.com', 'Leon', 'qwerty', 'Michaelerkuppel, 1010 Vienna, Austria'),
    (DATE('2019-10-12'), 'test@test.com', 'Adam', 'abc123', 'Nieuwezijds Voorburgwal 147, 1012 RJ Amsterdam, Netherlands'),
    (CURRENT_TIMESTAMP, 'user@user.com', 'Alicia', '12345678', 'Piazza del Colosseo, 1, 00184 Roma RM, Italy');


INSERT INTO lots
    (
        date_create,
        date_end,
        name,
        description,
        image_url,
        price_default,
        price_step,
        author_id,
        category_id,
        winner_id
    )
VALUES
    (
        CURRENT_TIMESTAMP,
        TIMESTAMP('2019-12-10'),
        N'2014 Rossignol District Snowboard',
        N'Snowboards widths are between 6 and 12 inches or 15 to 30 centimeters. Snowboards are differentiated from monoskis by the stance of the user.',
        'img/lot-1.jpg',
        10999,
        100,
        (SELECT id FROM users WHERE email='email@email.com'),
        (SELECT id FROM categories WHERE code='boards'),
        null
    ),
    (
        TIMESTAMP('2019-11-01'),
        TIMESTAMP('2020-01-04'),
        N'DC Ply Mens 2016/2017 Snowboard',
        N'Snowboards are boards where both feet are secured to the same board, which are wider than skis, with the ability to glide on snow.',
        'img/lot-2.jpg',
        159999,
        160,
        (SELECT id FROM users WHERE email='email@email.com'),
        (SELECT id FROM categories WHERE code='boards'),
        null
    ),
    (
        TIMESTAMP('2019-11-13'),
        TIMESTAMP('2020-02-01'),
        N'Крепления Union Contact Pro 2015 года размер L/XL',
        N'Designed by Union Pro Rider Gigi Rüf, the Contact Pro is one of the most sought after bindings in the Union line.',
        'img/lot-3.jpg',
        800,
        10,
        (SELECT id FROM users WHERE email='test@test.com'),
        (SELECT id FROM categories WHERE code='attachment'),
        null
    ),
    (
        CURRENT_TIMESTAMP,
        TIMESTAMP('2019-12-28'),
        N'Ботинки для сноуборда DC Mutiny Charocal',
        N'DC Mutiny Snowboard Boot - Size 11.5 Color: Black, Brand New. Shipped with USPS Priority Mail.',
        'img/lot-4.jpg',
        10999,
        110,
        (SELECT id FROM users WHERE email='test@test.com'),
        (SELECT id FROM categories WHERE code='boots'),
        null
    ),
    (
        CURRENT_TIMESTAMP,
        TIMESTAMP('2019-12-13'),
        N'Куртка для сноуборда DC Mutiny Charocal',
        N'Waterproof Guarantee To Keep You Dry.',
        'img/lot-5.jpg',
        7500,
        75,
        (SELECT id FROM users WHERE email='user@user.com'),
        (SELECT id FROM categories WHERE code='clothing'),
        null
    ),
    (
        TIMESTAMP('2019-11-02'),
        TIMESTAMP('2020-01-03'),
        N'Маска Oakley Canopy',
        N'Canopy™ allows you to oversize your field of view, without compromising fit.',
        'img/lot-6.jpg',
        5400,
        55,
        (SELECT id FROM users WHERE email='user@user.com'),
        (SELECT id FROM categories WHERE code='other'),
        null
    );

INSERT INTO bets
    (lot_id, date_bet, total, author_id)
VALUES
    (
        (SELECT l.id FROM lots l WHERE name = 'Маска Oakley Canopy'),
        TIMESTAMP('2019-11-20'),
        ((SELECT price_default FROM lots WHERE name = 'Маска Oakley Canopy') + (SELECT price_step FROM lots WHERE name = 'Маска Oakley Canopy')),
        (SELECT author_id FROM lots WHERE name = 'Маска Oakley Canopy')
    ),
    (
        (SELECT l.id FROM lots l WHERE name = 'Маска Oakley Canopy'),
        TIMESTAMP('2019-11-21'),
        ((SELECT price_default FROM lots WHERE name = 'Маска Oakley Canopy') + (SELECT price_step FROM lots WHERE name = 'Маска Oakley Canopy') * 2),
        (SELECT author_id FROM lots WHERE name = 'Маска Oakley Canopy')
    ),
    (
        (SELECT l.id FROM lots l WHERE name = 'DC Ply Mens 2016/2017 Snowboard'),
        TIMESTAMP('2019-11-13'),
        ((SELECT price_default FROM lots WHERE name = 'DC Ply Mens 2016/2017 Snowboard') + (SELECT price_step FROM lots WHERE name = 'DC Ply Mens 2016/2017 Snowboard')),
        (SELECT author_id FROM lots WHERE name = 'DC Ply Mens 2016/2017 Snowboard')
    );

-- GET ALL CATEGORIES
SELECT * FROM categories;

-- GET ALL NEW LOTS
SELECT l.name AS lot_name, price_default, image_url, MAX(total) AS price_total, c.name AS category_name
FROM lots l
JOIN categories c ON l.category_id = c.id
LEFT JOIN bets b ON b.lot_id = l.id
WHERE l.date_end > CURRENT_TIMESTAMP
GROUP BY l.id
ORDER BY l.date_create DESC;

-- GET LOT BY ID WITH CATEGORY NAME
SELECT *, cat.name
FROM lots l
    JOIN categories cat
        ON l.category_id = cat.id
WHERE l.id = 1;

-- UPDATE LOT NAME
UPDATE lots
SET name = 'NEW 2014 Rossignol District Snowboard :)'
WHERE id = 1;

-- GET ALL BETS BY LOT ID ORDER BY DATA
SELECT *
FROM bets
WHERE lot_id = 6
ORDER BY date_bet DESC;
