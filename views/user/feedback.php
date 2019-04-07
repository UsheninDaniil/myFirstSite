<?php


    if(isset($_POST["send"])){

        $from = htmlspecialchars ($_POST["from"]);
        $to = htmlspecialchars ($_POST["to"]);
        $subject = htmlspecialchars ($_POST["subject"]);
        $message = htmlspecialchars ($_POST["message"]);

        $_SESSION["from"] = $from;
        $_SESSION["to"] = $to;
        $_SESSION["subject"] = $subject;
        $_SESSION["message"] = $message;

        $result = User::check_feedback_form($from, $to, $subject, $message);

        $error_from = $result["error_from"];
        $error_to = $result["error_to"];
        $error_subject = $result["error_subject"];
        $error_message = $result["error_message"];
        $error = $result["error"];

    }
?>

    <h2>Обратная связь</h2>

<form name = "feedback" action="" method ="post" class="feedback">

    <label>От кого:</label><br />
    <input type="text" name="from" value="<?= (isset($_SESSION["from"]) ? $_SESSION["from"] : '') ?>" /><br />
    <?php if(isset($error_from)) : ?>
    <span style="color:red"><?=$error_from?></span>
    <?php endif; ?>

    <label>Кому:</label><br />
    <input type="text" name="to" value="<?= (isset($_SESSION["to"]) ? $_SESSION["to"] : '') ?>" /><br />
    <?php if(isset($error_to)) : ?>
    <span style="color:red"><?=$error_to?></span>
    <?php endif; ?>

    <label>Тема:</label><br />
    <input type="text" name="subject" value="<?= (isset($_SESSION["subject"]) ? $_SESSION["subject"] : '') ?>" /><br />
    <?php if(isset($error_subject)) : ?>
    <span style="color:red"><?=$error_subject?></span>
    <?php endif; ?>

    <label>Сообщение:</label><br />
    <textarea name = "message" colls ="30" rows="10"><?= (isset($_SESSION["message"]) ? $_SESSION["message"] : '') ?></textarea><br />
    <?php if(isset($error_message)) : ?>
    <span style= "color:red"><?=$error_message?></span>
    <?php endif; ?>

    <input type = "submit" name ="send" value="Отправить"><br />

</form>

