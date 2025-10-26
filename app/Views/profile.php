<?php ?>


<div class="container">
    <div class="box">
        <ul>
            <li><a href="/registration">Регистрация</a></li>
            <li><a href="/auth">Авторизация</a></li>
            <li><a href="/posts">Список всех постов</a></li>
            <li><a href="/logout">Выход</a></li>
        </ul>
    </div>
    <?php if(isset($_SESSION["user"])):?>
    <div class="box">
        <p>Добро пожаловать <?= $_SESSION["user"]["username"]?></p>
    </div>
    <div class="box">
        <p>Ваши данные:</p>
        <span>Ваш ID в системе: <?= $_SESSION["user"]["idUser"]?></span><br>
        <span>Email-адрес: <?= $_SESSION["user"]["email"]?></span><br>
        <span>Имя пользователя: <?= $_SESSION["user"]["username"]?></span><br>
    </div>
    <?php endif;?>
</div>