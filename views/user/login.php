<?php

    if(isset($_POST["login"])){

        $email = htmlspecialchars ($_POST["email"]);
        $password = htmlspecialchars ($_POST["password"]);

        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;

        User::check_login_form($email,$password);

        $error = User::check_login_form($email, $password)[0];
        $error_email = User::check_login_form($email, $password)[1];
        $error_password = User::check_login_form($email, $password)[2];

        if(!$error){
            User::action_authorization($email, $password);
        }
    }

?>

<h2>Вход</h2>

<form name = "login" action="" method ="post">

    <label>email:</label><br />
    <input type="text" name="email" value="<?= (isset($_SESSION["email"]) ? $_SESSION["email"] : "") ?>" /><br />
    <?php if(isset($error_email)) : ?>
    <span style="color:red"><?=$error_email?></span>
    <?php endif; ?>

    <label>Пароль:</label><br />
    <input type="text" name="password" value="<?= (isset($_SESSION["password"]) ? $_SESSION["password"] : "") ?>" /><br />
    <?php if (isset($error_password)) : ?>
    <span style="color:red"><?=$error_password?></span>
    <?php endif; ?>

    <input type = "submit" name ="login" value="Войти"><br />

</form>



