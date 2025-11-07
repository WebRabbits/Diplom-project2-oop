<?php
$this->layout("layout", [
    "title" => "Редактировать пост"
]);

$errors = $errors ?? [];

$getError = function ($field) use ($errors) {
    return $errors[$field][0] ?? "";
};

$hasErrors = !empty($errors);
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Заголовок страницы -->
            <div class="text-center mb-4">
                <h1 class="text-dark mb-3">Редактировать пост</h1>
                <p class="text-muted">Измените или дополните свой пост, сделайте его лучше!</p>
            </div>

            <!-- Блок общей ошибки валидации -->
            <?php if (!empty($getError("errException"))): ?>
                <div class="alert alert-danger" id="generalError">
                    Уведомление: <?= $getError("errException") ?>
                </div>
            <?php endif; ?>
            <!-- Форма добавления поста -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form id="editPostForm" method="post" enctype="multipart/form-data" novalidate="" class="<?= $hasErrors ? "was-validated" : "" ?>">
                        <!-- Поле для заголовка -->
                        <div class="mb-4">
                            <label for="postTitle" class="form-label fs-5 text-dark fw-semibold">
                                Заголовок поста
                            </label>
                            <input type="text"
                                name="title"
                                class="form-control form-control-lg <?= !empty($getError("title")) ? "is-invalid" : "" ?>"
                                id="postTitle"
                                placeholder="Введите заголовок поста"
                                value="<?= $old["title"] ?? $post->getTitle() ?>">
                            <div class="invalid-feedback">
                                <?= $getError("title") ?>
                            </div>
                        </div>

                        <!-- Поле для описания -->
                        <div class="mb-4">
                            <label for="description" class="form-label fs-5 text-dark fw-semibold">
                                Описание поста
                            </label>
                            <textarea class="form-control <?= !empty($getError("description")) ? "is-invalid" : "" ?>"
                                name="description"
                                id="postDescription"
                                rows="6"
                                placeholder="Введите описание поста"><?= $old["description"] ?? $post->getDescription() ?></textarea>
                            <div class="invalid-feedback">
                                <?= $getError("description") ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6>Последняя дата изменения: <?= $post->getDatePublished()?></h6>
                        </div>

                        <!-- Поле для загрузки картинки -->
                        <div class="mb-4">
                            <label for="image" class="form-label fs-5 text-dark fw-semibold">
                                Изображение поста
                            </label>
                            <input type="file"
                                name="image"
                                class="form-control <?= !empty($getError("image_post")) ? "is-invalid" : "" ?>"
                                id="postImage"
                                accept="image/*">
                            <div class="form-text">
                                Поддерживаемые форматы: PNG, JPG.
                            </div>
                            <div class="invalid-feedback">
                                <?= $getError("image_post") ?>
                            </div>
                        </div>

                        <!-- Предпросмотр изображения -->
                        <div class="mb-4" id="imagePreviewContainer">
                            <label class="form-label fs-6 text-dark fw-semibold">
                                Загруженное изображение
                            </label>
                            <div class="border rounded p-3 text-center">
                                <img id="imagePreview"
                                    src="<?= $post->getImagePost()?>"
                                    alt="Предпросмотр"
                                    class="img-fluid rounded"
                                    style="max-height: 300px;">
                                <!-- <p class="text-muted mb-0 d-none" id="noPreviewText">
                                    <img src="" alt="Загруженное изображение" style="min-height: 300px;">
                                </p> -->
                            </div>
                        </div>

                        <!-- Кнопки отправки формы -->
                        <div class="d-flex gap-3 justify-content-end pt-3">
                            <a href="/profile/<?= $_SESSION["user"]["idUser"]?>" class="btn btn-outline-secondary btn-lg">Отмена</a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                Редактировать
                            </button>
                            <?php 
                                $this->insert("post_actions", [
                                    "post" => $post,
                                    "allowedActions" => ["active", "inactive", "delete"]
                                ]);
                            ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Информационный блок -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h6 class="card-title text-dark mb-3">Полезная информация</h6>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">• Заголовок должен быть кратким и информативным. Не менее 10 и не более 100000 символов</li>
                        <li class="mb-2">• Описание должно полностью раскрывать тему поста. Не менее 10 и не более 100000 символов</li>
                        <li class="mb-2">• Изображение должно быть качественным и релевантным. Для загрузки поддерживаются только форматы файлов "PNG", "JPG"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script>
    // JavaScript для предпросмотра изображения и валидации
    document.getElementById('postImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const noPreviewText = document.getElementById('noPreviewText');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                noPreviewText.classList.add('d-none');
                previewContainer.classList.remove('d-none');
            }

            reader.readAsDataURL(file);
        } else {
            preview.classList.remove('d-none');
            noPreviewText.classList.remove('d-none');
        }
    });

    // Валидация формы
    // document.getElementById('addPostForm').addEventListener('submit', function(e) {
    //     e.preventDefault();

    //     const form = e.target;
    //     const generalError = document.getElementById('generalError');

    //     if (!form.checkValidity()) {
    //         e.stopPropagation();
    //         generalError.classList.remove('d-none');
    //         generalError.textContent = 'Пожалуйста, заполните все обязательные поля правильно';
    //     } else {
    //         generalError.classList.add('d-none');
    //         // Здесь будет отправка формы
    //         alert('Форма успешно отправлена!');
    //     }

    //     form.classList.add('was-validated');
    // });
</script>