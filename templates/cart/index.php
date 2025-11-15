<main>
<!--    --><?php //var_dump($closedCarts)?>
    <h1>Корзина</h1>
    <div class="menu">
        <a href="/cart/order"><button>Заказать</button></a>
        <a href="/products"><button>К товарам</button></a>
    </div>
    <div class="active">
        <?php if (count($cartItems) == 0):?>
            <h2>В корзине пока пусто</h2>
        <?php else:?>
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
                    <?php if (array_key_exists($product['id'], $cartItems)): ?>
                        <tr>
                            <td><a href="/products/<?= $product['id'] ?>"><?= $product['name'] ?></a></td>
                            <td><?= $product['price'] ?></td>
                            <td>
                                <div class="buttons">
                                    <form action="/cart/subtract" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="submit" value="-">
                                    </form>
                                    <p><?= $cartItems[$product['id']] ?></p>
                                    <form action="/cart/add" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="submit" value="+">
                                    </form>
                                </div>
                            </td>
                            <td><?= isset($cartItems[$product['id']]) ? $product['price'] * $cartItems[$product['id']] : 0 ?>руб.
                            </td>
                        </tr>
                    <?php endif; ?>

                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif;?>
    </div>
    <div class="closed">

    </div>
</main>
