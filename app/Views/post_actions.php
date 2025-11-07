<?php
$defaultActions = ["edit", "active", "inactive", "delete"];
$allowedActions = $allowedActions ?? $defaultActions;
?>

<div class="btn-group">
    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Действия
    </button>
    <ul class="dropdown-menu">
        <?php if (in_array("edit", $allowedActions)): ?>
            <li><a href="/posts/edit/<?= $post->getId() ?>" class="dropdown-item">Изменить пост</a></li>
        <?php endif; ?>

        <?php if (in_array("active", $allowedActions)): ?>
            <li><a href="/posts/active/<?= $post->getId() ?>" class="dropdown-item">Активировать</a></li>
        <?php endif; ?>

        <?php if (in_array("inactive", $allowedActions)): ?>
            <li><a href="/posts/inactive/<?= $post->getId() ?>" class="dropdown-item">Добавить в архив</a></li>
        <?php endif; ?>
        
        <?php if (in_array("delete", $allowedActions)): ?>
            <li><a href="/posts/delete/<?= $post->getId() ?>" class="dropdown-item">Удалить</a></li>
        <?php endif; ?>
    </ul>
</div>