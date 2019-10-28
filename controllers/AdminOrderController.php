<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 27.10.2019
 * Time: 22:56
 */

require_once('/models/Admin.php');
require_once('/models/AdminCategory.php');
require_once('/models/AdminParameter.php');
require_once('/models/AdminProduct.php');
require_once('/models/AdminReview.php');
require_once('/models/AdminOrder.php');
require_once('/models/Category.php');
require_once('/models/DatabaseConnect.php');
require_once('/models/Parameters.php');
require_once('/models/Product.php');
require_once('/models/Search.php');
require_once('/models/User.php');
require_once('/models/Color.php');
require_once('/components/Helper.php');
require_once('/controllers/AdminController.php');

class AdminOrderController
{
    public function actionOrdersControl(){

        $orders_list = AdminOrder::get_orders_list();
        require_once('/views/layouts/header.php');
        require_once('/views/admin/order/orders_control.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionViewOrderInformation(){

        $order_id = Helper::get_information_from_url(3);
        $order_information = AdminOrder::get_order_information($order_id);
        $product_list = AdminOrder::get_product_list_by_order_id($order_id);

        require_once('/views/layouts/header.php');
        require_once('/views/admin/order/view_order_information.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionDeleteSelectedOrder(){

        if(isset($_POST['order_id'])){
            $order_id = $_POST['order_id'];
            AdminOrder::delete_order($order_id);
        }

    }

}