
</div>



<!-- Footer Wrapper -->
<div id="footer-wrapper">
    <footer id="footer" class="container">
        <div class="row justify-content-around">

            <div class="col-2 col-6-medium col-12-small">
                <section>
                    <h5>Помощь</h5>
                    <ul class="footer_ul">
                        <li><a href ="/delivery">Доставка</a></li>
                        <li><a href ="/payment">Оплата</a></li>
                        <li><a href ="/feedback">Обратная связь</a></li>
                    </ul>
                </section>
            </div>

            <div class="col-3 col-6-medium col-12-small">
                <section>
                    <h5>Информация о компании</h5>
                    <ul class="footer_ul">
                        <li><a href ="/about">О нас</a></li>
                        <li><a href ="/contact">Контакты</a></li>
                        <li><a href ="/vacancy">Вакансии</a></li>
                    </ul>
                </section>
            </div>

            <div class="col-2 col-6-medium col-12-small">
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

        echo "<br/><b>uri шаблон = $uri</b>";

        foreach ($scripts_array as $script_src){
            echo $script_src;
            echo "<br/>";
            echo "<script src='$script_src'></script>";
        }
    }
}

?>

</div>









<!--<script defer src="/template/third_party_files/fontawesome-free-5.8.1-web/js/all.min.js"></script>-->
<!---->
<!--<script src="/template/third_party_files/jquery-3.3.1.min.js"></script>-->
<!--<script src="/template/third_party_files/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>-->
<!---->
<!--<script src="/template/third_party_files/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>-->
<!--<script src="/template/third_party_files/popper.min.js"></script>-->
<!--<script src="/template/third_party_files/bootstrap4-editable/js/bootstrap-editable.js"></script>-->
<!---->
<!--<script src="/template/third_party_files/jQuery-UI-Multiple-Select-Widget/src/jquery.multiselect.js"></script>-->
<!--<script src="/template/third_party_files/jQuery-UI-Multiple-Select-Widget/src/jquery.multiselect.filter.js"></script>-->
<!--<script src="/template/third_party_files/jQuery-UI-Multiple-Select-Widget/i18n/jquery.multiselect.ru.js"></script>-->
<!--<script src="/template/third_party_files/jQuery-UI-Multiple-Select-Widget/i18n/jquery.multiselect.filter.ru.js"></script>-->
<!---->
<!--<script src="/template/third_party_files/tagify-plugin-old-version/jQuery.tagify.js"></script>-->
<!---->
<!--<script src="/template/js/drag_and_drop.js"></script>-->
<!--<script src="/template/js/images_preview.js"></script>-->
<!--<script src="/template/js/product_images_slider.js"></script>-->
<!--<!---->-->
<script src="/template/js/main.js"></script>
<script src="/template/js/admin.js?v=<?php echo uniqid()?>"></script>

</body>
</html>

