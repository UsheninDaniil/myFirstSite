<?php

require_once('/models/Admin.php');
require_once('/models/AdminCategory.php');
require_once('/models/AdminParameter.php');
require_once('/models/AdminProduct.php');
require_once('/models/AdminReview.php');
require_once('/models/Category.php');
require_once('/models/DatabaseConnect.php');
require_once('/models/Parameters.php');
require_once('/models/Product.php');
require_once('/models/Search.php');
require_once('/models/User.php');

Class AdminController
{
    public function __construct(){
        Admin::check_if_administrator();
    }

    public function actionCabinet()
    {
        require_once ('/views/layouts/header.php');
        require_once ('/views/admin/cabinet.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionNoPermission()
    {
        require_once ('/views/layouts/header.php');
        require_once ('/views/admin/NoPermission.php');
        require_once ('/views/layouts/footer.php');
    }

}

?>
