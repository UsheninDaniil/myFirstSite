
</div>



<!-- Footer Wrapper -->
<div id="footer-wrapper">
    <footer id="footer" class="container">
        <div class="row justify-content-around">

            <div class="col-12 col-sm-6 col-md-4">
                <section>
                    <h5>Помощь</h5>
                    <ul class="footer_ul">
                        <li><a href ="/delivery">Доставка</a></li>
                        <li><a href ="/payment">Оплата</a></li>
                        <li><a href ="/feedback">Обратная связь</a></li>
                    </ul>
                </section>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <section>
                    <h5>Информация о компании</h5>
                    <ul class="footer_ul">
                        <li><a href ="/about">О нас</a></li>
                        <li><a href ="/contact">Контакты</a></li>
                        <li><a href ="/vacancy">Вакансии</a></li>
                    </ul>
                </section>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <section>
                    <dl class="contact">
                        <dt>График работы </dt>
                        <dd>
                            Пн-Пт 08:30-21:00 <br />
                            Сб-Вс 08:30-21:00
                        </dd>
                        <dt>Телефон</dt>
                        <dd>(098) 36-91-177</dd>
                    </dl>
                </section>
            </div>

            <div class="col-12">
                <div id="copyright">
                    <ul class="footer_ul">
                        <li>&copy; Untitled. All rights reserved</li>
                    </ul>
                </div>
            </div>


    </footer>
</div>


<?php

$path= ROOT.'/config/assets_routes.php';
$assets_routes = include($path);
//print_r($assets_routes);

$current_uri = $_SERVER['REQUEST_URI'];

foreach ($assets_routes as $uri => $scripts_array){

    $uri='^'.$uri;

    if (preg_match("~$uri~",$current_uri)){

//        echo "<br/><b>uri шаблон = $uri</b>";

        foreach ($scripts_array as $script_src){
//            echo $script_src;
//            echo "<br/>";
            echo "<script src='$script_src'></script>";
        }
    }
}

?>

</div>

<script src="/template/js/main.js"></script>
<script src="/template/js/admin.js?v=<?php echo uniqid()?>"></script>

</body>
</html>

