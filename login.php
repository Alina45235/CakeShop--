<?php
require __DIR__ . '/includes/header.php';
?>

<h1>Вход</h1>

<?php if (!empty($_SESSION['redirect_after_login'])): ?>
    <p style="background:#fff8e1; border-left:4px solid #ffb900; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        Чтобы оформить заказ, войдите или зарегистрируйтесь.
        <b>Корзина сохранится.</b>
    </p>
<?php endif; ?>

<form action="/SHOP/actions/login.php" method="post" style="max-width:400px;">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <input type="password" name="password" required>
    </div>

    <button class="btn" type="submit">Войти</button>
</form>

<p style="margin-top:16px;">Нет аккаунта? <a href="/SHOP/register.php">Зарегистрироваться</a></p>

<?php require __DIR__ . '/includes/footer.php'; ?>