<?php 
$errors = $errors ?? [];
// dd($errors);

$getError = function($field) use ($errors){
    return $errors[$field][0] ?? "";
}

?>

<?php if(isset($errors["errException"])):?>
    <p><?= $getError("errException")?></p>
<?php endif;?>
<form action="" method="post">
    <label for="email">Email</label>
    <input type="text" name="email">
    <span style="color:red; font-size:11px"><?= $getError("email")?></span><br>
    <label for="password">Password</label>
    <input type="text" name="password">
    <span style="color:red; font-size:11px"><?= $getError("password")?></span><br>

    <button type="submit">Авторизация</button>
</form>