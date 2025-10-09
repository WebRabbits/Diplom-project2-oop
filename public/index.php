<?php
session_start();

require_once(__DIR__ . "/../vendor/autoload.php");
// require_once(__DIR__ . "/../app/config/dbconnect.php");

use App\Models\User;
use App\Models\Post;
use App\Config\GetDataConfig;
use App\Database\Connection;
use App\Database\QueryBuilder;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use Aura\SqlQuery\QueryFactory;

// $user1 = new User(new UserRepository(Connection::Connect(), new QueryFactory("mysql")));

//// Регистрация пользователя
// $user1->registration("newUser14@mail.com", "111", "New user Name 144"); 
// echo "Привет " . $user1->getUsername();
// echo "Твой ID: " . $user1->getId();
// echo "Твой email: " . $user1->getEmail();


//// Авторизация пользователя
// $user1->login("newUser9@mail.com", "111");
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

// $result = $user1->getFindEmail("newUser6@mail.com");
// dd($result);


//============================================================

$post = new Post(new PostRepository(Connection::Connect(), new QueryFactory("mysql")));

//// Добавление нового поста
// $post->addPost("tt112", " text1232", $_FILES["image"]);
// echo $post->getId();
// echo $post->getTitle();
// echo $post->getDatePublished();
// echo $post->getImagePost();


// Получение объекта данных по конкретному посту при передачи ID-поста
// $res = $post->getPostById(991);
// dd($res);

//// Изменение существующего поста
// $result = $post->editPost(101, "", "ppool", $_FILES["image"]);
// echo $result;

//// Удаление поста
// $result = $post->deletePost( 104);
// echo $result;

//// Получение всех постов
// $result = $post->getAllPosts();
// dd($result);
// foreach ($result as $post) {
//     var_dump($post->title);
// }

//// Помещение поста В архив
// $res = $post->archive(97);
// dd($res);

//// Перемещение поста ИЗ архив
// $res = $post->unarchive(97);
// dd($res);

//// Загрузка картинки
// $post->uploadImage($_FILES["image"], 39);

//// Удаление картинки
// $post->deleteImage(89);


//// Работа с БД
// $res = GetDataConfig::Get("mysql.db"); // Получаем значение по переданному пути ключей из глобального конфига.
// dd($res);

// $db = Connection::Connect(); // Получаем подключение к БД реализованное через PDO - возвращается сам объект PDO.
// dd($db);


// $db = new QueryBuilder(Connection::Connect());
// $res = $db->getAll("posts"); // Получить все значения из таблицы БД
// dd($res); // Вернёт полный объекта класса QueryBuilder
// dd($res->result()); // Вернёт массив объектов всех полученных результатов из запроса
// dd($res->getOneResult()); // Вернёт ПЕРВЫЙ объект данных и результата запроса

// $res = $db->getByCondition("posts", "LIKE", ["description", "id", "image_post"], ["description" => "%desc%"]); // Получить значение/значения из таблицы БД по переданному условию
// dd($res);

// $res = $db->insert("posts", [
//     "title" => "Проверочка",
//     "description" => "123321",
//     "image_post" => "/img/postsimage_1_68df014f58401_17test.jpg"
// ]); // Добавление данных в таблицу БД
// dd($res);

// $db->update("posts", "=", ["title" => "checkUpdateQuery_1_TITLE"], ["id" => 76]); // Обновление данных в БД по заданному условию отбора записи/записей и переданному ассоциативному массиву полей=>значений для изменения этих полей и их значений в БД по конкретной записи/записям в таблице

// $db->delete("posts", 72); // Удаление данных из таблицы по преданному идентификатору записи в БД


?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="image">Загрузите картинку</label>
    <input type="file" name="image"><br>
    <button type="submit">Отправить</button>
</form>