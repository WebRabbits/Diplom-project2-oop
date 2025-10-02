<?php
session_start();

require_once(__DIR__ . "/../vendor/autoload.php");
// require_once(__DIR__ . "/../app/config/dbconnect.php");

use App\Models\User;
use App\Models\Post;

// $user1 = new User();

//// Регистрация пользователя
// $user1->registration("example3@mail.com", "Test112", "USer3 LAstName3"); 
// echo "Привет " . $user1->getUsername();
// echo "Твой ID: " . $user1->getId();
// echo "Твой email: " . $user1->getEmail();


//// Авторизация пользователя
// $user1->login("example3@mail.com", "Test112");
// $_SESSION["email"] = $user1->getEmail();
// $_SESSION["username"] = $user1->getUsername();
// echo "Email: " . $_SESSION["email"] . "Имя: " . $_SESSION["username"];

//// Деавторизация пользователя
// $user1->logout();
// echo $user1->getId();
// echo $user1->getEmail();
// echo $user1->getUsername();


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
// $post->addPost("Заголовок №5", "Новый пост №5", "/src/images/imags115");
// echo $post->getId();
// echo $post->getTitle();
// echo $post->getDatePublished();
// dd($res);

// Получение объекта данных по конкретному посту при передачи ID-поста
// $res = $post->getPostById(50);
// dd($res);
// echo $result;

//// Изменение существующего поста
// $result = $post->editPost(64, "Title post 64!! Super!", "Desc6", "/image/super64.jpeg");
// echo $result;

//// Удаление поста
// $result = $post->deletePost(64);
// echo $result;

//// Получение всех постов
// $result = $post->getAllPosts()->result();
// foreach ($result as $post) {
//     var_dump($post->id);
// }

//// Помещение поста В архив
// $post->archive(50);
// $res = $post->getPostById(50);
// dd($res);

//// Перемещение поста ИЗ архив
// $post->unarchive(50);
// $res = $post->getPostById(50);
// dd($res);

//// Загрузка картинки
// $post->uploadImage($_FILES["image"], 37);

//// Удаление картинки
// $post->deleteImage(37);

?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="image">Загрузите картинку</label>
    <input type="file" name="image"><br>
    <button type="submit">Отправить</button>
</form>
