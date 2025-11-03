<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="#"><img alt="logo" class="d-inline-block align-top mr-2" src="/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item ">
                <a class="nav-link" href="/">Главная</a>
            </li>
            <?php if (isset($_COOKIE["login"])): ?>
                <li class="nav-item ">
                    <a class="nav-link" href="/profile/<?= $_SESSION["user"]["idUser"] ?>">Профиль</a>
                </li>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav ml-auto">
            <?php if (!isset($_COOKIE["login"])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/registration">Регистрация</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auth">Войти</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Выйти</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>