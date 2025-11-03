<?php
$this->layout("layout", [
    "title" => "Профиль",
    "extra_css" => "/css/fa-regular.css"
]);

// dd($posts);
?>
<?php if (!empty($user)): ?>
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                Добро пожаловать! <?= $user->getUsername() ?>
            </h1>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xl-6 m-auto">
                <!-- profile summary -->
                <div class="card mb-g rounded-top">
                    <div class="row no-gutters row-grid">
                        <div class="col-12">
                            <div class="d-flex flex-column align-items-center justify-content-center p-4">
                                <img src="/img/demo/avatars/avatar-admin-lg.png" class="rounded-circle shadow-2 img-thumbnail" alt="">
                                <h5 class="mb-0 fw-700 text-center mt-3">
                                    <?= $user->getUsername() ?>
                                    <!-- <small class="text-muted mb-0">Toronto, Canada</small> -->
                                </h5>
                                <!-- <div class="mt-4 text-center demo">
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#C13584">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#4680C2">
                                            <i class="fab fa-vk"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#0088cc">
                                            <i class="fab fa-telegram"></i>
                                        </a>
                                    </div> -->
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 text-center">
                                <!-- <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mobile-alt text-muted mr-2"></i> +1 317-456-2564</a> -->
                                <a href="mailto:<?= $user->getEmail() ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                    <i class="fas fa-mouse-pointer text-muted mr-2"></i><?= $user->getEmail() ?></a>
                                <!-- <address class="fs-sm fw-400 mt-4 text-muted">
                                        <i class="fas fa-map-pin mr-2"></i> Восточные Королевства, Штормград 15
                                    </address> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($posts)): ?>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php foreach ($posts as $post): ?>
                    <div class="col mb-3">
                        <div class="card h-100 <?= $post->getIsActive() ? "border-info" : "border-dark" ?>">
                            <img src="<?= $post->getImagePost() ?>" class="card-img-top img-thumbnail" alt="" style="max-height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $post->getTitle() ?></h5>
                                <p class="card-text add-hidden-text-content"><?= $post->getDescription() ?></p>
                                <div class="row row-cols-1 row-cols-md-2">
                                    <div class="col">
                                        <a href="/posts/view/<?= $post->getId()?>" class="primary-text stretched-link text-start">Читать полностью...</a>
                                    </div>
                                    <div class="col">
                                        <?php if ($post->getIsActive()): ?>
                                            <p class="text-right text-success">Активен</p>
                                        <?php else: ?>
                                            <p class="text-right text-dark">Архивирован</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row row-cols-1 row-cols-md-2">
                                    <div class="col-md-auto">
                                        <small class="text-muted">Изменён <?= $post->getDatePublished() ?></small>
                                    </div>
                                    <div class="col-md-auto ms-md-auto">
                                        <div class="btn-group">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Действия
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="/posts/edit/<?= $post->getId()?>" class="dropdown-item">Изменить пост</a></li>
                                                <li><a href="" class="dropdown-item">Активировать</a></li>
                                                <li><a href="" class="dropdown-item">Добавить в архив</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
<?php endif; ?>