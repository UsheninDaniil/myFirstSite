

<div class="container_with_breadcrumb">
    <div class="breadcrumb">
        <a href="/cabinet" class="go-back-to-admin-panel" ><b>Личный кабинет</b></a>
    </div>
</div>

<div style="padding: 0 1em;">
    <?php
    echo "Имя: ".$_SESSION["login"];
    echo "<br />Права: $user_role";
    ?>

    <div style="display: flex; justify-content: center; flex-direction: column; padding-top: 20px">

    <?php if ($user_role = "admin"): ?>
        <div><a href="/admin" class="cool_link_style"> Панель администратора </a></div>
    <?php endif; ?>

    <div><a href="/cabinet/orders_list" class="cool_link_style"> Список заказов </a></div>
    <div><a href="javascript:void(0);" class="logout_link cool_link_style"> Выйти с учетной записи </a></div><br />

    <form class="logout_form" action="" method ="post"></form>

    </div>


</div>





