<main>
    <h1>Товары</h1>
    <div class="menu">
        <a href="/cart"><button>Моя корзина</button></a>
        <?php if(!isset($_SESSION['user_id'])):?>
        <a href="/login"><button>Авторизация</button></a>
        <a href="/register"><button>Регистрация</button></a>
        <?php else:?>
            <a href="/logout"><button>Выйти</button></a>
        <?php endif;?>
    </div>
    <table>
        <thead>
        <tr>
            <td>Наименование продукта</td>
            <td>Цена продукта</td>
            <td>Корзина</td>
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
                            <form action="/products/subtract" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="submit" value="-">
                            </form>
                            <p><?= $cartItems[$product['id']] ?></p>
                            <form action="/products/add" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="submit" value="+">
                            </form>
                        <?php else: ?>
                            <form action="/products/add" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="submit" value="Добавить в корзину">
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
