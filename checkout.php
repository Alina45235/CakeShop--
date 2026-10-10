<?php
require __DIR__ . '/includes/header.php';


if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = '/SHOP/checkout.php';
    redirect('/SHOP/login.php');
}

$cart = $_SESSION['cart'] ?? [];
if (!$cart) redirect('/SHOP/cart.php');

$total = cart_total($pdo);
?>

<h1>Оформление заказа</h1>

<form action="/SHOP/actions/place_order.php" method="post" style="max-width:500px;">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div class="form-group">
        <label>Имя получателя *</label>
        <input type="text" name="name" required value="<?= e($_SESSION['user_name'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label>Телефон *</label>
        <input type="text" name="phone" required>
    </div>
    <div class="form-group">
        <label>Адрес доставки *</label>
        <textarea name="address" rows="3" required></textarea>
    </div>
    <div class="form-group">
        <label>Дата доставки</label>
        <input type="date" name="delivery_date" min="<?= date('Y-m-d') ?>">
    </div>
    <div class="form-group">
        <label>Комментарий</label>
        <textarea name="comment" rows="2"></textarea>
    </div>

    <p style="font-size:20px; margin:20px 0;">
        Итого: <b><?= number_format($total, 0, '.', ' ') ?> ₽</b>
    </p>
    <p style="color:#888; margin-bottom:20px;">Оплата при получении</p>

    <button class="btn" type="submit">Оформить заказ</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>