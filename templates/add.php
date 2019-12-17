<form
    class="form form--add-lot container"
    action="/add.php"
    method="post"
    enctype="multipart/form-data"
>
    <h2>Добавление лота</h2>

    <div class="form__container-two">
        <div class="form__item<?= isset($errors['lot-name']) ? ' form__item--invalid' : ''; ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input
                id="lot-name"
                type="text"
                name="lot-name"
                value="<?= getPostValue('lot-name') ?>"
                placeholder="Введите наименование лота"
                required
            >
            <span class="form__error">Введите наименование лота</span>
        </div>
        <div class="form__item<?= isset($errors['category']) ? ' form__item--invalid' : ''; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $category): ?>
                <?php $category_id = escapeString($category['id']); ?>
                    <option
                        value="<?= $category_id; ?>"
                        <?= $category_id === getPostValue('category') ? 'selected' : ''; ?>
                    ><?= escapeString($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>

    <div class="form__item form__item--wide<?= isset($errors['message']) ? ' form__item--invalid' : ''; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" required name="message" placeholder="Напишите описание лота"><?= trim(getPostValue('message')); ?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>

    <div class="form__item form__item--file<?= isset($errors['lot-img']) ? ' form__item--invalid' : ''; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" name="lot-img" required type="file" id="lot-img" value="">
            <label for="lot-img">
                Добавить
            </label>
        </div>
    </div>

    <div class="form__container-three">
        <div class="form__item form__item--small<?= isset($errors['lot-rate']) ? ' form__item--invalid' : ''; ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input
                id="lot-rate"
                type="number"
                name="lot-rate"
                placeholder="0"
                required
                value="<?= getPostValue('lot-rate') ?>"
            >
            <span class="form__error">Введите начальную цену</span>
        </div>
        <div class="form__item form__item--small<?= isset($errors['lot-step']) ? ' form__item--invalid' : ''; ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input
                id="lot-step"
                type="number"
                name="lot-step"
                placeholder="0"
                required
                value="<?= getPostValue('lot-step') ?>"
            >
            <span class="form__error">Введите шаг ставки</span>
        </div>

        <div class="form__item<?= isset($errors['lot-date']) ? ' form__item--invalid' : ''; ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input
                required
                class="form__input-date"
                id="lot-date"
                type="text"
                name="lot-date"
                value="<?= getPostValue('lot-date') ?>"
                placeholder="Введите дату в формате ГГГГ-ММ-ДД"
            >
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>

    <?php if (isset($errors)): ?>
        <div class="form__errors">
            <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><strong><?= $error; ?></strong></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
