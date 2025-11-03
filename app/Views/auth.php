<?php
$this->layout("layout", [
    "title" => "Авторизация",
    "hide_header" => true,
    "extra_css" => "/css/page-login-alt.css"
]);

$errors = $errors ?? [];
// dd($errors);

$getError = function ($field) use ($errors) {
    return $errors[$field][0] ?? "";
};

$hasErrors = !empty($errors);
?>

<div class="blankpage-form-field">
    <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
        <a href="/" class="page-logo-link press-scale-down d-flex align-items-center">
            <img src="img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
            <span class="page-logo-text mr-1">Учебный проект</span>
            <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
        </a>
    </div>
    <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
        <?php if(!empty($_SESSION["reg_complete"])):
            session_unset();
            ?>
        <div class="alert alert-success">
            Регистрация успешна
        </div>
        <?php endif;?>
        <?php if(!empty($errors)):?>
        <div class="alert alert-danger">
            <?= $getError("errException")?>
        </div>
        <?php endif;?>
        <form id="js-login" action="" method="post" novalidate="" class="<?= $hasErrors ? "was-validated" : ""?>">
            <div class="form-group">
                <label class="form-label" for="username">Email</label>
                <input type="email" id="username" name="email" placeholder="Эл. адрес" class="form-control <?= !empty($getError("email")) ? "is-invalid" : "" ?>" value="<?= $_POST["email"] ?? ""?>">
                <div class="invalid-feedback"><?= $getError("email")?></div>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-control <?= !empty($getError("password")) ? "is-invalid" : "" ?>" value="<?= $_POST["password"] ?? ""?>">
                <div class="invalid-feedback"><?= $getError("password")?></div>
            </div>
            <button type="submit" id="js-login-btn" class="btn btn-default float-right">Войти</button>
        </form>
    </div>
    <div class="blankpage-footer text-center">
        Нет аккаунта? <a href="/registration"><strong>Зарегистрироваться</strong>
    </div>
</div>
<video poster="img/backgrounds/clouds.png" id="bgvid" playsinline autoplay muted loop>
    <source src="media/video/cc.webm" type="video/webm">
    <source src="media/video/cc.mp4" type="video/mp4">
</video>
<script src="js/vendors.bundle.js"></script>
<!-- <script>
    $("#js-login-btn").click(function(event) {

        // Fetch form to apply custom Bootstrap validation
        var form = $("#js-login")

        if (form[0].checkValidity() === false) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.addClass('was-validated');
        // Perform ajax submit here...
    });
</script> -->