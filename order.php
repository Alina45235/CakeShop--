<?php
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$id, current_user_id()]);
$order = $stmt->fetch();

if (!$order) {
    http_response_code(404);
    echo '<h1>Заказ не найден</h1>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT oi.*, p.name
                       FROM order_items oi
                       LEFT JOIN products p ON p.id = oi.product_id
                       WHERE oi.order_id = ?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>

<a href="/SHOP/account.php" style="color:#888; text-decoration:none;">← К заказам</a>

<h1>Заказ #<?= $order['id'] ?></h1>
<p>Дата: <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></p>
<p>Статус: <span class="<?= status_class($order['status']) ?>"><?= status_label($order['status']) ?></span></p>

<h3 style="margin-top:20px;">Состав заказа</h3>
<table>
    <thead>
        <tr>
            <th>Торт</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $it): ?>
        <tr>
            <td><?= e($it['name'] ?? 'Удалён') ?></td>
            <td><?= number_format($it['price'], 0, '.', ' ') ?> ₽</td>
            <td><?= $it['qty'] ?></td>
            <td><?= number_format($it['price'] * $it['qty'], 0, '.', ' ') ?> ₽</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="text-align:right;">Итого:</th>
            <th><?= number_format($order['total'], 0, '.', ' ') ?> ₽</th>
        </tr>
    </tfoot>
</table>

<h3 style="margin-top:20px;">Доставка</h3>
<p>Получатель: <?= e($order['name']) ?></p>
<p>Телефон: <?= e($order['phone']) ?></p>
<p>Адрес: <?= e($order['address']) ?></p>
<?php if ($order['delivery_date']): ?>
    <p>Дата: <?= date('d.m.Y', strtotime($order['delivery_date'])) ?></p>
<?php endif; ?>
<?php if ($order['comment']): ?>
    <p>Комментарий: <?= e($order['comment']) ?></p>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>