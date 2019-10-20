
<h2>Вход</h2>

<form name = "login" action="" method ="post">

    <label>email:</label><br />
    <input type="text" name="email" value="ushenin.danil2017@mail.ru" /><br />
    <?php if(isset($email_error)) : ?>
    <span style="color:red"><?=$email_error?><br/></span>
    <?php endif; ?>

    <label>Пароль:</label><br />
    <input type="password" name="password" value="0853553" /><br />
    <?php if(isset($password_error)): ?>
    <span style="color:red"><?=$password_error?><br/></span>
    <?php endif; ?>

    <input type = "submit" name ="login" value="Войти"><br />

</form>



