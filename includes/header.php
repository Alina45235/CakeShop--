<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин тортов</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/SHOP/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="/SHOP/" class="logo">CakeShop</a>
        <nav class="main-nav">
            <a href="/SHOP/">Каталог</a>
            <a href="/SHOP/cart.php">Корзина (<?= cart_count() ?>)</a>
            
            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <!-- Для админа: сразу ссылка на заказы и в админку -->
                    <a href="/SHOP/admin/orders.php">Заказы</a>
                    <a href="/SHOP/admin/">Админка</a>
                <?php else: ?>
                    <!-- Для обычного юзера: личный кабинет -->
                    <a href="/SHOP/account.php">Кабинет</a>
                <?php endif; ?>
                <a href="/SHOP/logout.php">Выйти</a>
            <?php else: ?>
                <a href="/SHOP/login.php">Войти</a>
                <a href="/SHOP/register.php">Регистрация</a>
            <?php endif; ?>
            
        </nav>
    </div>
</header>

<main class="container">
<?php if ($m = flash()): ?>
    <div class="flash"><?= e($m) ?></div>
<?php endif; ?>