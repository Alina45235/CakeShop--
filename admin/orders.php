<?php
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_auth.php';

$status = $_GET['status'] ?? '';
$where = [];
$params = [];

if ($status && in_array($status, ['new','confirmed','preparing','delivering','done','cancelled'])) {
    $where[] = "status = ?";
    $params[] = $status;
}

$sql = "SELECT * FROM orders";
if ($where) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>

<h1>Заказы</h1>

<div style="margin:20px 0; display:flex; gap:8px; flex-wrap:wrap;">
    <a href="/SHOP/admin/orders.php" class="btn btn-sm <?= !$status ? '' : 'btn-secondary' ?>">Все</a>
    <?php foreach (['new','confirmed','preparing','delivering','done','cancelled'] as $s): ?>
        <a href="/SHOP/admin/orders.php?status=<?= $s ?>" class="btn btn-sm <?= $status === $s ? '' : 'btn-secondary' ?>">
            <?= status_label($s) ?>
        </a>
    <?php endforeach; ?>
</div>

<table>
    <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Клиент</th>
            <th>Телефон</th>
            <th>Сумма</th>
            <th>Статус</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= date('d.m.Y H:i', strtotime($o['created_at'])) ?></td>
            <td><?= e($o['name']) ?></td>
            <td><?= e($o['phone']) ?></td>
            <td><?= number_format($o['total'], 0, '.', ' ') ?> ₽</td>
            <td><span class="<?= status_class($o['status']) ?>"><?= status_label($o['status']) ?></span></td>
            <td><a href="/SHOP/admin/order_view.php?id=<?= $o['id'] ?>" class="btn btn-sm">Открыть</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../includes/footer.php'; ?>