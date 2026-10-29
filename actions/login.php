<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/SHOP/login.php');
csrf_check();

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($pass, $user['password_hash'])) {
    flash('Неверный email или пароль');
    redirect('/SHOP/login.php');
}

$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['role']      = $user['role'];


$redirect = $_SESSION['redirect_after_login'] ?? '/SHOP/account.php';
unset($_SESSION['redirect_after_login']);

flash('Добро пожаловать, ' . $user['name'] . '!');
redirect($redirect);