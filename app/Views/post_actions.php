<?php
$defaultActions = ["edit", "publish", "draft", "archived", "delete"];
$allowedActions = $allowedActions ?? $defaultActions;
?>

<div class="btn-group">
    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Действия
    </button>
    <ul class="dropdown-menu">
        <?php if (in_array("edit", $allowedActions)): ?>
            <li><a href="/posts/edit/<?= $post->getId()->getValue() ?>" class="dropdown-item">Изменить пост</a></li>
        <?php endif; ?>

        <?php if (in_array("publish", $allowedActions)): ?>
            <li><a href="/posts/publish/<?= $post->getId()->getValue() ?>" class="dropdown-item">Активировать</a></li>
        <?php endif; ?>

        <?php if(in_array("draft", $allowedActions)):?>
            <li><a href="/posts/draft/<?= $post->getId()->getValue() ?>" class="dropdown-item">Добавить в черновик</a></li>
        <?php endif; ?>

        <?php if (in_array("archived", $allowedActions)): ?>
            <li><a href="/posts/archived/<?= $post->getId()->getValue() ?>" class="dropdown-item">Добавить в архив</a></li>
        <?php endif; ?>
        
        <?php if (in_array("delete", $allowedActions)): ?>
            <li><a href="/posts/delete/<?= $post->getId()->getValue() ?>" class="dropdown-item">Удалить</a></li>
        <?php endif; ?>
    </ul>
</div>