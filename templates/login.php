<form
    class="form container<?= isset($errors) && count($errors) ? '  form--invalid' : ''; ?>"
    action="/login.php"
    method="post"
    autocomplete="off"
>
    <h2>Вход</h2>
    <div class="form__item<?= isset($errors['email']) ? ' form__item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" required value="<?= getPostValue('email') ?>">
        <span class="form__error"><?= isset($errors['email']) ? $errors['email'] : ''; ?></span>
    </div>
    <div class="form__item form__item--last<?= isset($errors['password']) ? ' form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" required value="<?= getPostValue('password') ?>">
        <span class="form__error"><?= isset($errors['password']) ? $errors['password'] : ''; ?></span>
    </div>
    <strong class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</strong>
    <button type="submit" class="button">Войти</button>
</form>
