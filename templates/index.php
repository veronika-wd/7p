<main>
    <h1>Товары</h1>
    <table>
        <thead>
        <tr>
            <td>Наименование продукта</td>
            <td>Цена продукта</td>
            <td>Корзина</td>
            <td>Стоимость товаров в корзине</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><a href="/products/<?= $product['id'] ?>"><?= $product['name'] ?></a></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <div class="buttons">
                        <?php if (array_key_exists($product['id'], $cartItems)): ?>
                            <form action="/cart/subtract" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="submit" value="-">
                            </form>
                            <p><?= $cartItems[$product['id']] ?></p>
                            <form action="/cart/add" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="submit" value="+">
                            </form>
                        <?php else: ?>
                            <form action="/cart/add" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="submit" value="Добавить в корзину">
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
                <td><?= isset($cartItems[$product['id']]) ? $product['price'] * $cartItems[$product['id']] : 0 ?>руб.
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
