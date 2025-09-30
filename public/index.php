<?php 

require_once(__DIR__ . "/../vendor/autoload.php");

use App\Models\User;
use App\Models\Post;

$user1 = new User("test1@gmail.com", "test111");
var_dump($user1);

$post1 = new Post("New Post 1", "Desc post1", "image.png");
var_dump($post1);

?>