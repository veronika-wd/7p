<main>
    <h1>Товары</h1>
    <?php foreach($products as $idx => $product): ?>
    <?=$product['name']?>
    <?=$product['price']?>
        <a href="/products/<?=$product['id']?>">Подробнее</a>

<div class="buttons">
    <!--    если уже есть, то плюс минус-->
    <?php
    $count = 0;

    foreach ($cartItems as $cartItem) {
        if ($cartItem['product_id'] == $product['id']){
            $count = $cartItem['count'];
        }
    }
    if ($count != 0) :
        ?>
        <form action="/cart/subtract" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="-">
        </form>
        <p><?= $count ?></p>
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="+">
        </form>
    <?php else:?>
        <!--а если еще нету то:-->
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Добавить в корзину">
        </form>
    <?php endif;?>
</div>

    <?php endforeach;?>
</main>
