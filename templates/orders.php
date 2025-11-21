<main>
    <h1>Мои заказы</h1>
    <div class="menu">
        <a href="/cart"><button>Моя корзина</button></a>
        <a href="/products"><button>К товарам</button></a>
    </div>

    <?php foreach ($orders as $order):?>
    <h3>Заказ №<?=$order['id']?></h3>
        <table>
            <thead>
            <tr>
                <td>Товар</td>
                <td>Цена</td>
                <td>Количество</td>
                <td>Стоимость</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orderItemsGrouped[$order['cart_id']] as $orderItem):?>
                <tr>
                    <td><?=$orderItem['product_name']?></td>
                    <td><?=$orderItem['price']?></td>
                    <td><?=$orderItem['count']?></td>
                    <td><?=$orderItem['count'] * $orderItem['price']?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    <?php endforeach; ?>

</main>