<?php
$posts = $posts ?? [];
// dd($posts);
?>

<div class="container" style=" display: flex; flex-direction: column;">
    <div class="box">
        <p>Страница всех постов</p>
    </div>
    <div class="box" style=" display: flex; flex-direction: column; align-items: flex-start;">
        <a href="/posts/add">Добавить пост</a>
    </div>

    <?php if (isset($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="box" style=" display: flex; flex-direction: column; align-items: flex-start; border: 1px solid purple; padding: 5px; margin-bottom: 10px;">
                <p><?= $post->getTitle()?></p>
                <span><?= $post->getDescription()?></span>
                <span>Дата: <?= $post->getDatePublished()?></span>
                <span>Картинка поста:</span>
                <img src="<?= $post->getImagePost()?>" alt="" style="width: 50px; height: 50px;">
                <span><?= $post->getIsActive() == 1 ? "Активный" : "Не активный"?></span>
                <a href="/posts/edit/<?= $post->getId()?>">Редактировать пост</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>






    <!-- <div class="box" style=" display: flex; flex-direction: column; align-items: flex-start;">
        <p>Заголовок №1</p>
        <span>Описание №1</span>
        <span>Дата: 2025-10-20 15:41:48</span>
        <span>Картинка поста:</span>
        <img src="image_3_68e83c68b04d1_1760050280.jpg" alt="">
        <button type="submit">Добавить пост</button>
        <button type="submit">Редактировать пост</button>
        <button type="submit">Удалить пост</button>
        <button type="submit">Сделать пост НЕактивным</button>
        <button type="submit">Сделать пост Активным</button>
    </div> -->
</div>