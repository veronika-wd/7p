<main>
    <div class="sidebar">
        <a href="/"><h2>Категории</h2></a>
        <a href="/catalog"><button>Каталог</button></a>
        <?php foreach ($categories as $category) :?>
            <a href="/<?= $category['slug']?>"><?=$category['name']?></a>
        <?php endforeach;?>
        <?php if(isset($_SESSION['user_id'])):?>
            <p>Текущий пользователь:<?=$user['login']?></p>
            <a href="/profile"><button>Перейти в профиль</button></a>
            <a href="/logout"><button>Выход</button></a>
            <?php if($user['is_admin']):?>
                <a href="/categories"><button>Перейти к админ-панели</button></a>
            <?php endif;?>
        <?php else: ?>
            <a href="/login"><button>Войти</button></a>
        <?php endif; ?>
    </div>
    <div class="main-cont">
        <div class="category-cont">
            <h2>Категории</h2>
            <?php
            if (isset($childCategories)):?>
            <?php foreach ($childCategories as $childCategory) :?>
                <a href="/<?=$childCategory['slug']?>"><button><?=$childCategory['name']?></button></a>
            <?php endforeach;?>
            <?php endif;?>
        </div>
        <h2>Список товаров</h2>

        <div class="card-container">
            <?php foreach ($goods as $good) :?>
                <div class="card">
                    <h3><?=$good['name']?></h3>
                    <p><?= $good['category_name']?></p>
                    <hr>
                    <div class="prices-block">
                        <p>до 1 сут.</p>
                        <p><?=$good['price_one']?> руб.</p>
                        <p>от 2 до 5 сут.</p>
                        <p><?=$good['price_two']?> руб.</p>
                        <p>от 5 сут.</p>
                        <p><?=$good['price_three']?> руб.</p>
                    </div>
                    <a href="/show/<?=$good['slug']?>">Подробнее</a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</main>
