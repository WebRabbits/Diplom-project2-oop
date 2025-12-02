<?php

namespace App\Repositories;

use App\Models\ValueObject\Post\CreatorId;
use App\Models\ValueObject\Post\Description;
use App\Models\ValueObject\Post\ImagePost;
use App\Models\ValueObject\Post\PostId;
use App\Models\ValueObject\Post\Status;
use App\Models\ValueObject\Post\Title;
use App\Services\ImageUploadService;
use App\Repositories\Interfaces\PostRepositoriesInterface;
use Aura\SqlQuery\QueryFactory;
use DateTime;
use PDO;
use App\Models\Post;

class PostRepository implements PostRepositoriesInterface
{
    private PDO $pdo;
    private QueryFactory $queryFactory;
    private ImageUploadService $imageUpload;

    public function __construct(PDO $pdo, QueryFactory $queryFactory, ImageUploadService $imageUpload)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
        $this->imageUpload = $imageUpload;
    }

    public function create(Post $post)
    {
        // dd($post);

        // int $idCreator, string $title, string $description, array $image
        // $uploadImage = $this->uploadImage(new ImagePost($post->getImagePost()->getValue()));

        $insert = $this->queryFactory->newInsert();
        $insert->into("posts")->cols([
            "id_creator" => $post->getCreatorId()->getValue(),
            "title" => $post->getTitle()->getValue(),
            "description" => $post->getDescription()->getValue(),
            "image_post" => $post->getImagePost()->getValue(),
            "is_active" => $post->getStatus()->getValue()
        ]);
        $stmt = $this->pdo->prepare($insert->getStatement());
        $stmt->execute($insert->getBindValues());

        $postId =  new PostId($this->pdo->lastInsertId());

        $data = $this->findById($postId);

        return $data ? Post::createPost($data->getId(), $data->getCreatorId(), $data->getTitle(), $data->getDescription(), $data->getImagePost()) : false;
    }

    public function update(Post $post)
    {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols([
            "id_creator" => $post->getCreatorId()->getValue(),
            "title" => $post->getTitle()->getValue(),
            "description" => $post->getDescription()->getValue(),
            "date" => date("Y-m-d H:i:s"),
            "image_post" => $post->getImagePost()->getValue()
        ])->where("id = :id", ["id" => $post->getId()->getValue()]);

        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        if ($stmt->rowCount() > 0) {

            return $post ? $post : false;
        }
    }

    public function delete(Post $post)
    {
        $this->imageUpload->delete($post->getImagePost()->getValue());
        $delete = $this->queryFactory->newDelete();
        $delete->from("posts")->where("id = :id", ["id" => $post->getId()->getValue()])->bindValue("id", $post->getId()->getValue());
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
    public function findById(PostId $id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("posts")->where("id = :id", ["id" => $id->getValue()]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetch(PDO::FETCH_OBJ);
        return $data ? $this->createPostFromData($data) : null; // При добавлении DI контейнера - заменить на эту строку
    }

    public function findPostsByCreator(CreatorId $creatorId)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("posts")->where("id_creator = :id_creator", ["id_creator" => $creatorId->getValue()])->orderBy(["date DESC"]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (empty($data)) {
            return [];
        }

        foreach ($data as $post) {
            $posts[] = $this->createPostFromData($post);
        }

        return is_array($posts) ? $posts : false;
    }
    public function makeArchived(Status $status, PostId $id)
    {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => $status->getValue()])->where("id = :id", ["id" => $id->getValue()]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount() ? true : false;
    }
    public function makePublished(Status $status, PostId $id, DateTime $publishedTime)
    {
        
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => $status->getValue(), "date" => $publishedTime->format('Y-m-d H:i:s')])->where("id = :id", ["id" => $id->getValue()]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount() ? true : false;
    }

    public function makeDraft(Status $status, PostId $id) {
        $update = $this->queryFactory->newUpdate();
        $update->table("posts")->cols(["is_active" => $status->getValue()])->where("id = :id", ["id" => $id->getValue()]);
        $stmt = $this->pdo->prepare($update->getStatement());
        $stmt->execute($update->getBindValues());

        return $stmt->rowCount() ? true : false;
    }

    // public function uploadImage(array $image)
    // {
    //     $arrayMIMEType = ["image/png", "image/jpeg"];
    //     $nameFile = pathinfo($image["name"], PATHINFO_FILENAME);
    //     $extensionFile = "." . pathinfo($image["name"], PATHINFO_EXTENSION);
    //     $typeFile = $image["type"];
    //     $tmpName = $image["tmp_name"];
    //     $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/public/img/posts/";
    //     dd($targetDirectory);
    //     $targetFile = $targetDirectory . $nameFile . "_" . uniqid() . "_" . time() . $extensionFile;
    //     $relativePathImage = "/img/posts/" . basename($targetFile);

    //     if (!in_array($typeFile, $arrayMIMEType)) {
    //         echo "Недоступный формат файла для загрузки";
    //         return;
    //     }

    //     if (is_uploaded_file($tmpName)) {
    //         if (!move_uploaded_file($tmpName, $targetFile)) {
    //             echo "Произошла ошибка при загрузке файла на сервер";
    //             return;
    //         } else {
    //             return $relativePathImage;
    //         }
    //     }
    // }

    // public function deleteImage(PostId $id)
    // {
    //     $select = $this->queryFactory->newSelect();
    //     $select->from("posts")->cols(["image_post"])->where("id = :id", ["id" => $id->getValue()]);
    //     $stmt = $this->pdo->prepare($select->getStatement());
    //     $stmt->execute($select->getBindValues());

    //     $dataImage = $stmt->fetch(PDO::FETCH_OBJ);

    //     $targetDirectory = $_SERVER["DOCUMENT_ROOT"];
    //     $targetFile = $dataImage->image_post;

    //     $file = $targetDirectory . $targetFile;

    //     if (isset($dataImage)) {
    //         if (file_exists($file)) {
    //             unlink($file);
    //             return;
    //         }
    //     }

    //     // echo "Произошла непредвиденная ошибка при удалении файла.<br>Ранее загруженный файл не был удалён из системы";
    //     return false;
    // }

    // При добавлении DI контейнера - использовать данный метод, чтобы вернуть Объект класса User, а не stdClass
    public function createPostFromData($data): Post
    {
        // dd($data);
        $postId = new PostId($data->id);
        $creatorId = new CreatorId($data->id_creator);
        $title = new Title($data->title);
        $description = new Description($data->description);
        $imagePost = new ImagePost($data->image_post, "image/png");
        $status = new Status($data->is_active);
        $datePublished = null;

        if(!empty($data->date)) {
            $datePublished = new DateTime($data->date);
        }

        // if (is_array($data)) {
        //     return new Post(
        //         $postId,
        //         $creatorId,
        //         $title,
        //         $description,
        //         $imagePost
        //     );
        // }

        // return new Post(
        //     $postId,
        //     $creatorId,
        //     $title,
        //     $description,
        //     $imagePost
        // );
        return Post::createFromData($postId, $creatorId, $title, $description, $imagePost, $status, $datePublished);
    }
}
