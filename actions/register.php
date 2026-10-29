<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/SHOP/register.php');
csrf_check();

$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$pass  = $_POST['password'] ?? '';

if (!$name || !$email || strlen($pass) < 6) {
    flash('Заполните все поля, пароль минимум 6 символов');
    redirect('/SHOP/register.php');
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    flash('Такой email уже зарегистрирован');
    redirect('/SHOP/register.php');
}

$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $phone, $hash]);

$_SESSION['user_id']   = $pdo->lastInsertId();
$_SESSION['user_name'] = $name;
$_SESSION['role']      = 'user';


$redirect = $_SESSION['redirect_after_login'] ?? '/SHOP/account.php';
unset($_SESSION['redirect_after_login']);

flash('Регистрация успешна!');
redirect($redirect);