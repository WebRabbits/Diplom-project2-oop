<?php
$this->layout("layout", ["title" => $post->getTitle()->getValue()]);

// dd($post->getIMagePost());
// dd($creatorName);
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Левая часть - основной контент (70%) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Заголовок -->
                    <h1 class="card-title text-dark mb-4 pb-2 border-bottom border-primary border-3">
                        <?= $post->getTitle()->getValue() ?>
                    </h1>

                    <!-- Картинка -->
                    <div class="mb-4">
                        <img src="<?= $post->getImagePost()->getValue() ?>"
                            alt="Изображение поста"
                            class="img-fluid rounded-3 w-100"
                            style="max-height: 400px; object-fit: contain;">
                    </div>

                    <!-- Описание -->
                    <div class="card-text">
                        <p class="fs-5 text-secondary lh-base">
                            Описание:
                        </p>
                        <p class="text-muted lh-lg">
                            <?= $post->getDescription()->getValue() ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая часть - сайдбар (30%) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Информация о создателе -->
                    <div class="border-start border-primary border-4 ps-3 mb-4">
                        <small class="text-muted d-block">Создатель поста</small>
                        <h6 class="text-dark mb-1 fw-bold"><?= $creatorName ?></h6>
                        <small class="text-muted"><?= $creatorEmail ?></small>
                    </div>

                    <!-- Дата изменения -->
                    <?php if($post->getPublishedTime()):?>
                    <div class="border-start border-success border-4 ps-3 mb-4">
                        <small class="text-muted d-block">Дата последнего изменения</small>
                        <span class="text-dark fw-semibold"><?= $post->formatDateTime() ?></span>
                    </div>
                    <?php endif;?>

                    <!-- Статус поста -->
                    <div class="border-start border-warning border-4 ps-3 mb-4">
                        <small class="text-muted d-block">Статус поста</small>
                        <?php if ($post->isPublishedPost()): ?>
                            <span class="text-success fw-semibold">● Активен</span>
                        <?php elseif ($post->isDraftPost()): ?>
                            <span class="text-dark fw-semibold">● Черновик</span>
                        <?php else:?>
                            <span class="text-dark fw-semibold">● Архивирован</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Дополнительные действия -->
            <!-- <div class="card shadow-sm border-0 mt-3">
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm">
                            Редактировать пост
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            Архивировать
                        </button>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>