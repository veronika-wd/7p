<main>
    <h1>Товары</h1>
    <?php foreach($products as $product): ?>
    <?=$product['name']?>
    <?=$product['price']?>
        <a href="/products/<?=$product['id']?>">Подробнее</a>
        <form action="/cart/add" method="post">
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Добавить в корзину">
        </form>
    <?php endforeach;?>
</main>
