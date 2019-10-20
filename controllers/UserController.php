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
require_once('/models/Color.php');
require_once('/components/Validator.php');

class UserController
{
    public function actionRegistration()
    {
        require_once('/views/layouts/header.php');
        require_once('/views/user/registration.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionLogin()
    {
        if (isset($_POST["login"])) {
            $email = htmlspecialchars($_POST["email"]);
            $password = htmlspecialchars($_POST["password"]);

            User::check_login_form($email, $password);

            $rules = [$email => 'email', $password => 'password'];

            $result = Validator::validate_data($rules);

            if ($result['validity_result'] === true) {
                User::action_authorization($email, $password);
            } else {
                foreach ($result['warnings_list'] as $key => $warning) {
                    if($key === 'email'){
                        $email_error = $warning;
                    }
                    if($key === 'password'){
                        $password_error = $warning;
                    }
                }
            }
        }

        require_once('/views/layouts/header.php');
        require_once('/views/user/login.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionFeedback()
    {
        require_once('/views/layouts/header.php');
        require_once('/views/user/feedback.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionCabinet()
    {
        if (User::action_check_authorization() == false) {
            header("Location: /login");
        }

        if (isset($_POST["logout"])) {
            session_destroy();
            header("Location: /login");
        }

        $user_id = $_SESSION["user_id"];
        $user_role = Admin::check_user_role($user_id);
        $order_list = User::get_order_list_by_user_id($user_id);

        require_once('/views/layouts/header.php');
        require_once('/views/user/cabinet.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionCart()
    {
        if (isset($_SESSION['cart_product_list'])) {
            $cartData = unserialize($_SESSION['cart_product_list']);
        } else {
            $cartData = [];
        }

        if (isset($_POST["order"])) {
            $cartData = serialize($cartData);
            $current_date = date("m.d.y");
            $current_time = date("H:i");
            if (isset($_SESSION["user_id"])) {
                $user_id = $_SESSION["user_id"];
            } else {
                $user_id = "";
            }

            User::make_an_order($cartData, $user_id, $current_date, $current_time);
            header("Location: /cabinet");
        }

        if (isset($_POST["delete_product_from_cart_list"])) {
            $delete_product_id = $_POST["delete_product_id"];
            $delete_product_color = $_POST["delete_product_color"];
            User::delete_product_color_from_cart_list($delete_product_id, $delete_product_color);
        }

        require_once('/views/layouts/header.php');

        if (isset($_SESSION['cart_product_list'])) {
            require_once(ROOT . '/views/user/cart.php');
        } else {
        }
        require_once('/views/layouts/footer.php');
    }


    public function actionCompareCategoryProducts()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        if (isset($segments[2])) {
            $category_id = $segments[2];
        }

        if (isset($_SESSION['compare_product_list'])) {
            $compareData = unserialize($_SESSION['compare_product_list']);
            if (isset($compareData[$category_id])) {
                $compare_product_list_of_selected_category = $compareData[$category_id];
            }
        } else {
            $compareData = [];
        }

        require_once('/views/layouts/header.php');

        if (isset($compareData[$category_id])) {
            require_once(ROOT . '/views/user/compare_category_products.php');
        }
        require_once('/views/layouts/footer.php');
    }


    public function actionCompare()
    {
        if (isset($_SESSION['compare_product_list'])) {
            $compareData = unserialize($_SESSION['compare_product_list']);
            $min_category_id = key($compareData);
            foreach ($compareData as $category_id => $information) {
                if ($category_id < $min_category_id) {
                    $min_category_id = $category_id;
                }
            }

            header("Location: /compare_category/$min_category_id");
        } else{
            require_once('/views/layouts/header.php');
            require_once('/views/layouts/footer.php');
        }
    }


}