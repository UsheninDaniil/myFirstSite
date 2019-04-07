<?php

    if(isset($_POST["register"])){

        $login = htmlspecialchars ($_POST["login"]);
        $password = htmlspecialchars ($_POST["password"]);
        $email = htmlspecialchars ($_POST["email"]);

        $_SESSION["login"] = $login;
        $_SESSION["password"] = $password;
        $_SESSION["email"] = $email;

        $result=User::check_register_form($login, $password, $email);

        $error = $result["error"];
        $error_login = $result["error_login"];
        $error_password = $result["error_password"];
        $error_email = $result["error_email"];

        if(!$error){
            User::action_register_new($login, $password, $email);
            header ("Location: successregistration.php");
            exit;
        }
    }

?>

    <h2>Регистрация</h2>

<form name = "registration" action="" method ="post">

    <label>Логин:</label><br />
    <input type="text" name="login" value="<?= (isset($_SESSION["login"]) ? $_SESSION["login"] : '')?>" /><br />
    <?php if(isset($error_login)) : ?>
    <span style="color:red"><?=$error_login?></span><br />
    <?php endif; ?>

    <label>Пароль:</label><br />
    <input type="text" name="password" value="<?= (isset($_SESSION["password"]) ? $_SESSION["password"] : '') ?>" /><br />
    <?php if(isset($error_password)) : ?>
    <span style="color:red"><?=$error_password?></span><br />
    <?php endif; ?>

    <label>email:</label><br />
    <input type="text" name="email" value="<?= (isset($_SESSION["email"]) ? $_SESSION["email"] : '') ?>" /><br />
    <?php if(isset($error_email)) : ?>
    <span style="color:red"><?=$error_email?></span><br />
    <?php endif; ?>

    <input type = "submit" name ="register" value="Зарегистрировать"><br />


</form>


