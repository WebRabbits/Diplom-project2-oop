<?php 
$errors = $errors ?? [];
// dd($errors);

$getError = function($field) use ($errors) {
    return $errors[$field][0] ?? "";
}
?>

<div class="container" style="   display: flex;flex-direction: column;">
    <p>Форма добавления нового поста</p>
    <div class="box">
        <form action="" method="post" enctype="multipart/form-data" style="display: flex;flex-direction: column;align-items: flex-start;flex-wrap: nowrap;">
            <input type="text" name="title" placeholder="Заголовок">
            <span style="color:red; font-size:11px"><?= $getError("title")?></span><br>
            <textarea name="description" cols="30" rows="10" placeholder="Описание поста"></textarea>
            <span style="color:red; font-size:11px"><?= $getError("description")?></span><br>
            <label for="image_post">Выберите картинку поста:</label>
            <input type="file" name="image">
            <span style="color:red; font-size:11px"><?= $getError("image_post")?></span><br>

            <button type="submit">Добавить</button>
        </form>
    </div>
</div>