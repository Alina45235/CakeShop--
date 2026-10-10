<?php
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/auth.php';

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([current_user_id()]);
$orders = $stmt->fetchAll();
?>

<h1>Мои заказы</h1>

<?php if (!$orders): ?>
    <p>У вас пока нет заказов. <a href="/SHOP/">Перейти в каталог</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Дата</th>
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
                <td><?= number_format($o['total'], 0, '.', ' ') ?> ₽</td>
                <td><span class="<?= status_class($o['status']) ?>"><?= status_label($o['status']) ?></span></td>
                <td><a href="/SHOP/order.php?id=<?= $o['id'] ?>" class="btn btn-sm">Подробнее</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>