<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PostRepositoriesInterface;
use Aura\SqlQuery\QueryFactory;
use PDO;
use App\Models\Post;

class PostRepository implements PostRepositoriesInterface
{
    private PDO $pdo;
    private QueryFactory $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function create(int $idCreator, string $title, string $description, array $image)
    {
        $uploadImage = $this->uploadImage($image);

        $insert = $this->queryFactory->newInsert();
        $insert->into("posts")->cols([
            "id_creator" => $idCreator,
            "title" => $title,
            "description" => $description,
            "image_post" => $uploadImage
        ]);
        $stmt = $this->pdo->prepare($insert->getStatement());
        $stmt->execute($insert->getBindValues());

        $postId =  $this->pdo->lastInsertId();

        $data = $this->findById($postId);
        
        return $data ? Post::getPost($data->getId(), $data->getIdCreator(), $data->getTitle(), $data->getDescription(), $data->getDatePublished(), $data->getImagePost(), $data->getIsActive()) : false;
    }

    public function update(int $id, int $idCreator, string $title, string $description, array $image)
    {
        $post = $this->findById($id);
        if(empty($image["tmp_name"])) {
            $uploadImage = $post->getImagePost();
        } else {
            $uploadImage = $this->uploadImage($image);
            $this->deleteImage($id);
        }

        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols([
            "id_creator" => $idCreator,
            "title" => $title,
            "description" => $description,
            "image_post" => $uploadImage
        ])->where("id = :id", ["id" => $id]);

        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        if($stmt->rowCount() > 0) {

            return $post ? $post : false;
        }
    }

    public function delete(int $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete->from("posts")->where("id = :id", ["id" => $id])->bindValue("id", $id);
        $stmt = $this->pdo->prepare($delete->getStatement());
        $stmt->execute($delete->getBindValues());

        return $stmt->rowCount() > 0 ? true : false;
    }
    public function getAll()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("posts")->orderBy(["date DESC"]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (empty($data)) {
            return [];
        }

        foreach ($data as $post) {
            $posts[] = $this->createPostFromData($post);
        }
        // dd($posts);
        // die;

        return is_array($posts) ? $posts : false;

        // return $data ? $this->createPostFromData($data) : false; // При добавлении DI контейнера - заменить на эту строку
    }
    public function findById(int $id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("posts")->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetch(PDO::FETCH_OBJ);
        return $data ? $this->createPostFromData($data) : null; // При добавлении DI контейнера - заменить на эту строку
    }
    public function makeInactive(int $id)
    {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => (int)false])->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount() ? true : false;
    }
    public function makeActive(int $id)
    {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => (int)true])->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount() ? true : false;
    }

    public function uploadImage(array $image)
    {
        $arrayMIMEType = ["image/png", "image/jpeg"];
        $nameFile = pathinfo($image["name"], PATHINFO_FILENAME);
        $extensionFile = "." . pathinfo($image["name"], PATHINFO_EXTENSION);
        $typeFile = $image["type"];
        $tmpName = $image["tmp_name"];
        $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/public/img/posts/";
        dd($targetDirectory);
        $targetFile = $targetDirectory . $nameFile . "_" . uniqid() . "_" . time() . $extensionFile;
        $relativePathImage = "/img/posts/" . basename($targetFile);

        if (!in_array($typeFile, $arrayMIMEType)) {
            echo "Недоступный формат файла для загрузки";
            return;
        }

        if (is_uploaded_file($tmpName)) {
            if (!move_uploaded_file($tmpName, $targetFile)) {
                echo "Произошла ошибка при загрузке файла на сервер";
                return;
            } else {
                return $relativePathImage;
            }
        }
    }

    public function deleteImage(int $idPost)
    {
        $select = $this->queryFactory->newSelect();
        $select->from("posts")->cols(["image_post"])->where("id = :id", ["id" => $idPost]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());
        
        $dataImage = $stmt->fetch(PDO::FETCH_OBJ);
        // $this->result = $this->db->getByCondition(table: "posts", operator: "=", columns: ["image_post"], where: ["id" => $id])->getOneResult();
        // dd($dataImage->image_post);
        // die;
        
        $targetDirectory = $_SERVER["DOCUMENT_ROOT"];
        $targetFile = $dataImage->image_post;
        
        $file = $targetDirectory . $targetFile;

        // dd(dirname($_SERVER["DOCUMENT_ROOT"]));
        // dd($dataImage);
        // dd($targetDirectory);
        // dd($targetFile);
        // dd($file);
        // die;

        if (isset($dataImage)) {
            if (file_exists($file)) {
                unlink($file);
                return;
            }
        }

        // echo "Произошла непредвиденная ошибка при удалении файла.<br>Ранее загруженный файл не был удалён из системы";
        return false;
    }

    // При добавлении DI контейнера - использовать данный метод, чтобы вернуть Объект класса User, а не stdClass
    public function createPostFromData($data): Post
    {
        if (is_array($data)) {
            return new Post(
                $data["id"],
                $data["id_creator"],
                $data["title"],
                $data["description"],
                $data["date"],
                $data["image_post"],
                (bool)$data["is_active"]
            );
        }
        
        return new Post(
            $data->id,
            $data->id_creator,
            $data->title,
            $data->description,
            $data->date,
            $data->image_post,
            (bool)$data->is_active
        );
    }
}
