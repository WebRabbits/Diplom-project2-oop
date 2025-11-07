<?php 
$errors = $errors ?? [];
// dd($errors);

// dd($post);

$getError = function($field) use ($errors) {
    return $errors[$field][0] ?? "";
}

?>

<?php if(isset($post)):?>
    <?php if(isset($errors["errException"])):?>
        <p style="color: brown;">Общая ошибка: <?= $getError("errException")?></p>
    <?php endif?>
<div class="container" style="   display: flex;flex-direction: column;">
    <p>Редактирование поста с ID: <?= $post->getId()?></p>
    <form action="/posts/actions" method="post">
        <input type="hidden" name="id" value="<?= $post->getId()?>">
        <label for="action">Действия:</label>
        <select name="action" required class="actionPost">
            <option value="" disabled selected>Выберите действие</option>
            <option value="delete">Удалить пост</option>
            <option value="active">Активировать пост</option>
            <option value="inactive">Добавить пост в архив</option>
            <option value="any">any</option>
        </select>
    </form>
    <div class="box">
        <form action="/posts/edit/<?= $post->getId()?>" method="post" enctype="multipart/form-data" style="display: flex;flex-direction: column;align-items: flex-start;flex-wrap: nowrap;">
            <input type="text" name="title" placeholder="Заголовок" value="<?= $post->getTitle()?>">
            <span style="color:red; font-size:11px"><?= $getError("title")?></span><br>
            <textarea name="description" cols="30" rows="10" placeholder="Описание поста"><?= $post->getDescription()?></textarea>
            <span style="color:red; font-size:11px"><?= $getError("description")?></span><br>
            <img src="<?= $post->getImagePost()?>" alt="" style="width: 100px; height: 100px;">
            <label for="image_post">Выберите картинку поста:</label>
            <input type="file" name="image">
            <span style="color:red; font-size:11px"><?= $getError("image_post")?></span><br>

            <button type="submit">Изменить описание</button>
        </form>
    </div>
</div>
<?php endif;?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".actionPost").addEventListener("change", function(event) {
            if(this.value && confirm("Подтвердите ваше действие")) {
                this.form.submit();
            } else{
                this.selectedIndex = 0;
            }
        })
    })
</script>