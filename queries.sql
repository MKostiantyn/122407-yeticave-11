INSERT INTO categories
    (name, code)
VALUES
    ('Доски и лыжи', 'boards-skis'),
    ('Крепления', 'bindings'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothes'),
    ('Инструменты', 'equipment'),
    ('Разное', 'sundry');


INSERT INTO users
    (date_registration, email, name, password, contacts)
VALUES
    (CURRENT_TIMESTAMP, 'email@email.com', 'Leon', 'qwerty', 'Michaelerkuppel, 1010 Vienna, Austria'),
    (TIMESTAMP('2019-10-12'), 'test@test.com', 'Adam', 'abc123', 'Nieuwezijds Voorburgwal 147, 1012 RJ Amsterdam, Netherlands'),
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
        '2014 Rossignol District Snowboard',
        'Snowboards widths are between 6 and 12 inches or 15 to 30 centimeters. Snowboards are differentiated from monoskis by the stance of the user.',
        'img/lot-1.jpg',
        10999,
        100,
        (SELECT id FROM users WHERE email='email@email.com'),
        (SELECT id FROM categories WHERE code='boards-skis'),
        null
    ),
    (
        TIMESTAMP('2019-11-01'),
        TIMESTAMP('2020-01-04'),
        'DC Ply Mens 2016/2017 Snowboard',
        'Snowboards are boards where both feet are secured to the same board, which are wider than skis, with the ability to glide on snow.',
        'img/lot-2.jpg',
        159999,
        160,
        (SELECT id FROM users WHERE email='email@email.com'),
        (SELECT id FROM categories WHERE code='boards-skis'),
        null
    ),
    (
        TIMESTAMP('2019-11-13'),
        TIMESTAMP('2020-02-01'),
        'Крепления Union Contact Pro 2015 года размер L/XL',
        'Designed by Union Pro Rider Gigi Rüf, the Contact Pro is one of the most sought after bindings in the Union line.',
        'img/lot-3.jpg',
        800,
        10,
        (SELECT id FROM users WHERE email='test@test.com'),
        (SELECT id FROM categories WHERE code='bindings'),
        null
    ),
    (
        CURRENT_TIMESTAMP,
        TIMESTAMP('2019-12-28'),
        'Ботинки для сноуборда DC Mutiny Charocal',
        'DC Mutiny Snowboard Boot - Size 11.5 Color: Black, Brand New. Shipped with USPS Priority Mail.',
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
        'Куртка для сноуборда DC Mutiny Charocal',
        'Waterproof Guarantee To Keep You Dry.',
        'img/lot-5.jpg',
        7500,
        75,
        (SELECT id FROM users WHERE email='user@user.com'),
        (SELECT id FROM categories WHERE code='clothes'),
        null
    ),
    (
        TIMESTAMP('2019-11-02'),
        TIMESTAMP('2020-01-03'),
        'Маска Oakley Canopy',
        'Canopy™ allows you to oversize your field of view, without compromising fit.',
        'img/lot-6.jpg',
        5400,
        55,
        (SELECT id FROM users WHERE email='user@user.com'),
        (SELECT id FROM categories WHERE code='sundry'),
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
SELECT l.name, price_default, image_url, total, cat.name
FROM lots l
    JOIN categories cat
        ON l.category_id = cat.id
    JOIN (
        SELECT MAX(total) total, b.lot_id
        FROM bets b
        GROUP BY b.lot_id
    ) newtable ON newtable.lot_id = l.id
WHERE l.date_end > CURRENT_TIMESTAMP
ORDER BY l.date_create DESC;

-- GET LOT BY ID WITH CATEGORY NAME
SELECT *, cat.name
FROM lots l
    JOIN categories cat
        ON l.category_id = cat.id
WHERE l.id = 1;

-- UPDATE LOT NAME
UPDATE lots
SET name = 'New Name :)'
WHERE id = 1;

-- GET ALL BETS BY LOT ID ORDER BY DATA
SELECT *
FROM bets
WHERE lot_id = 6
ORDER BY date_bet DESC;
