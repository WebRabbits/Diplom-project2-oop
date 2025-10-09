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

    public function create(string $title, string $description, string $image)
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into("posts")->cols([
            "title" => $title,
            "description" => $description,
            "image_post" => $image
        ]);
        $stmt = $this->pdo->prepare($insert->getStatement());
        $stmt->execute($insert->getBindValues());

        return $this->pdo->lastInsertId();
    }

    public function update(int $id, string $title, string $description, string $image) {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols([
            "title" => $title,
            "description" => $description,
            "image_post" => $image
        ])->where("id = :id", ["id" => $id]);

        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount();
    }

    public function delete(int $id) {
        $delete = $this->queryFactory->newDelete();
        $delete->from("posts")->where("id = :id", ["id" => $id])->bindValue("id", $id);
        $stmt = $this->pdo->prepare($delete->getStatement());
        $stmt->execute($delete->getBindValues());

        return $stmt->rowCount();
    }
    public function getAll() {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("posts");
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $data;

        // return data ? $this->createPostFromData($data) : null; // При добавлении DI контейнера - заменить на эту строку
    }
    public function findById(int $id) {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("posts")->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetch(PDO::FETCH_OBJ);

        return $data;

        // return data ? $this->createPostFromData($data) : null; // При добавлении DI контейнера - заменить на эту строку
    }
    public function makeInactive(int $id) {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => (int)false])->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount();
    }
    public function makeActive(int $id) {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => (int)true])->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount();
    }

    public function uploadImage(array $image)
    {
        $arrayMIMEType = ["image/png", "image/jpeg"];
        $nameFile = pathinfo($image["name"], PATHINFO_FILENAME);
        $extensionFile = "." . pathinfo($image["name"], PATHINFO_EXTENSION);
        $typeFile = $image["type"];
        $tmpName = $image["tmp_name"];
        $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/img/posts/";
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
        dd($dataImage->image_post);
        // die;
        
        $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]);
        $targetFile = $dataImage->image_post;
        
        $file = $targetDirectory . $targetFile;
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

        echo "Произошла непредвиденная ошибка при удалении файла.<br>Ранее загруженный файл нге был удалён из системы";
        return false;
    }

    // При добавлении DI контейнера - использовать данный метод, чтобы вернуть Объект класса User, а не stdClass
    // public function createPostFromData($data) {
    //     return new Post(
    //         $data->id,
    //         $data->title,
    //         $data->description,
    //         $data->date,
    //         $data->image_post,
    //         $data->isActive
    //     );
    // }
}
