<?php
require __DIR__ . '/includes/header.php';
?>

<h1>Регистрация</h1>

<?php if (!empty($_SESSION['redirect_after_login'])): ?>
    <p style="background:#fff8e1; border-left:4px solid #ffb900; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        Чтобы оформить заказ, зарегистрируйтесь.
        <b>Корзина сохранится.</b>
    </p>
<?php endif; ?>

<form action="/SHOP/actions/register.php" method="post" style="max-width:400px;">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div class="form-group">
        <label>Имя</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Телефон</label>
        <input type="text" name="phone">
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <input type="password" name="password" required minlength="6">
    </div>

    <button class="btn" type="submit">Зарегистрироваться</button>
</form>

<p style="margin-top:16px;">Уже есть аккаунт? <a href="/SHOP/login.php">Войти</a></p>

<?php require __DIR__ . '/includes/footer.php'; ?>