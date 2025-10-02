<?php

require_once(__DIR__ . "/../vendor/autoload.php");
// require_once(__DIR__ . "/../app/config/dbconnect.php");

use App\Models\User;
use App\Models\Post;

// $user1 = new User();

//// Регистрация пользователя
// $result = $user1->registration("example9@mail.com", "TEst22", "test2User"); 
// echo $result;

//// Авторизация пользователя
// $result = $user1->login("example8@mail.com", "Testik");
// echo $result;

//// Деавторизация пользователя
// $result = $user1->logout();
// echo $result;

//// Геттеры. Получения отдельных данных по авторизованному пользователю
// $result = $user1->getId();
// echo $result;

// $result = $user1->getEmail();
// echo $result;

// $result = $user1->getUsername();
// echo $result;

//============================================================

$post = new Post();

//// Добавление нового поста
// $result = $post->addPost("Заголовок №4", "Описание №4", "/src/images/image4");
// echo $result;

//// Изменение существующего поста
// $result = $post->editPost(26, "", "Desc6", "");
// echo $result;

//// Удаление поста
// $result = $post->deletePost(181);
// echo $result;

//// Получение всех постов
// $result = $post->getAllPosts();
// var_dump($result);

//// Помещение поста В архив
// $result = $post->archive(17);
// echo $result;

//// Перемещение поста ИЗ архив
// $result = $post->unarchive(17);
// echo $result;
