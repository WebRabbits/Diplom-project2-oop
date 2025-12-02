<?php
$this->layout("layout", [
    "title" => "Каталог постов"
]);

// dd($allPosts);

// foreach($allPosts as $post) {
//     dd($post->isPublishedPost());
//     dd($post->getStatus()->getValue());
// }
?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center text-dark mb-3">Все посты пользователей</h1>
            <p class="text-center text-muted">Общее количество активных постов: <?= count($allPosts) ?></p>
        </div>
        <?php if (isset($_SESSION["success"])): ?>
            <div class="alert alert-success">
                <?php
                echo "Уведомление: " . $_SESSION["success"];
                unset($_SESSION["success"]);
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php if (!empty($allPosts)): ?>
            <?php foreach ($allPosts as $post): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border">
                        <div class="card-body">
                            <h5 class="card-title text-dark mb-3"><?= $post->getTitle()->getValue() ?></h5>
                            <div class="mb-3">
                                <img src="<?= $post->getImagePost()->getValue() ?>"
                                    alt="Изображение поста"
                                    class="img-fluid rounded w-100"
                                    style="height: 200px; object-fit: contain;">
                            </div>
                            <p class="card-text text-muted mb-3 add-hidden-text-content">
                                <?= $post->getDescription()->getValue() ?>
                            </p>
                            <div class="border-start border-primary border-3 ps-2 mb-2">
                                <small class="text-muted">Создатель:</small>
                                <div class="text-dark fw-semibold"><?= isset($creatorData) ? $creatorData[$post->getCreatorId()->getValue()] : "Неизвестный" ?></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($post->getPublishedTime()): ?>
                                    <small class="text-muted"><?= $post->formatDateTime() ?></small>
                                <?php endif; ?>
                                <?php if ($post->isPublishedPost()): ?>
                                    <span class="badge bg-success">Активен</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <div class="d-grid">
                                <a href="/posts/view/<?= $post->getId()->getValue() ?>" class="btn btn-outline-primary btn-sm">Читать полностью</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Пагинация -->
    <!-- <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Навигация по страницам">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Назад</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Вперед</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div> -->
</div>