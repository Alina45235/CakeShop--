<?php
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_auth.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) redirect('/SHOP/admin/orders.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $newStatus = $_POST['status'] ?? '';
    $allowed = ['new','confirmed','preparing','delivering','done','cancelled'];

    if (in_array($newStatus, $allowed)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
        flash('Статус обновлён');
        redirect('/SHOP/admin/order_view.php?id=' . $id);
    }
}

$stmt = $pdo->prepare("SELECT oi.*, p.name
                       FROM order_items oi
                       LEFT JOIN products p ON p.id = oi.product_id
                       WHERE oi.order_id = ?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>

<a href="/SHOP/admin/orders.php" style="color:#888; text-decoration:none;">← К заказам</a>

<h1>Заказ #<?= $order['id'] ?></h1>

<p>Дата: <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></p>
<p>Текущий статус: <span class="<?= status_class($order['status']) ?>"><?= status_label($order['status']) ?></span></p>

<form method="post" style="margin:20px 0; display:flex; gap:10px; align-items:center;">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label>Сменить статус:</label>
    <select name="status" style="padding:8px; border:1px solid #ddd; border-radius:8px;">
        <?php foreach (['new','confirmed','preparing','delivering','done','cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>>
                <?= status_label($s) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Сохранить</button>
</form>

<h3>Состав заказа</h3>
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

<h3>Доставка</h3>
<p>Получатель: <?= e($order['name']) ?></p>
<p>Телефон: <?= e($order['phone']) ?></p>
<p>Адрес: <?= e($order['address']) ?></p>
<?php if ($order['delivery_date']): ?>
    <p>Дата: <?= date('d.m.Y', strtotime($order['delivery_date'])) ?></p>
<?php endif; ?>
<?php if ($order['comment']): ?>
    <p>Комментарий: <?= e($order['comment']) ?></p>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>