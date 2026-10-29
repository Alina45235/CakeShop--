<?php
require __DIR__ . '/includes/header.php';

$cart  = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;

if ($cart) {
    $ids = array_keys($cart);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $p) {
        $qty     = $cart[$p['id']];
        $sum     = $p['price'] * $qty;
        $total  += $sum;
        $items[] = ['product' => $p, 'qty' => $qty, 'sum' => $sum];
    }
}
?>

<h1>Корзина</h1>

<?php if (!$items): ?>
    <p>Корзина пуста. <a href="/SHOP/">Перейти в каталог</a></p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Торт</th>
                <th>Цена</th>
                <th>Кол-во</th>
                <th>Сумма</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr>
                <td><?= e($it['product']['name']) ?></td>
                <td><?= number_format($it['product']['price'], 0, '.', ' ') ?> ₽</td>
                <td>
                    <form action="/SHOP/actions/update_cart.php" method="post" style="display:flex; gap:6px;">
                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                        <input type="hidden" name="id" value="<?= $it['product']['id'] ?>">
                        <input type="number" name="qty" value="<?= $it['qty'] ?>" min="1" max="20" style="width:70px; padding:6px; border:1px solid #ddd; border-radius:6px;">
                        <button class="btn btn-sm" type="submit">OK</button>
                    </form>
                </td>
                <td><b><?= number_format($it['sum'], 0, '.', ' ') ?> ₽</b></td>
                <td>
                    <a href="/SHOP/actions/remove_from_cart.php?id=<?= $it['product']['id'] ?>"
                       class="btn btn-sm btn-danger"
                       data-confirm="Удалить из корзины?">×</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">Итого:</th>
                <th colspan="2"><?= number_format($total, 0, '.', ' ') ?> ₽</th>
            </tr>
        </tfoot>
    </table>

    <div style="text-align:right; margin-top:20px;">
        <?php if (is_logged_in()): ?>
            <a href="/SHOP/checkout.php" class="btn">Оформить заказ</a>
        <?php else: ?>
            <a href="/SHOP/login.php" class="btn">Войти и оформить</a>
            <p style="color:#888; margin-top:10px; font-size:14px;">
                Корзина сохранится после входа.
            </p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>