
<h2>Вход</h2>

<form name = "login" action="" method ="post">

    <label>email:</label><br />
    <input type="text" name="email" value="ushenin.danil2017@mail.ru" /><br />
    <?php if(isset($error_email)) : ?>
    <span style="color:red"><?=$error_email?></span>
    <?php endif; ?>

    <label>Пароль:</label><br />
    <input type="password" name="password" value="0853553" /><br />
    <?php if (isset($error_password)) : ?>
    <span style="color:red"><?=$error_password?></span>
    <?php endif; ?>

    <input type = "submit" name ="login" value="Войти"><br />

</form>



