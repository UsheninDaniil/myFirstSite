<?php

require_once(ROOT. '/models/User.php');


class UserController
{
    public function actionRegistration()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/user/registration.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionLogin()
    {
        if(isset($_POST["login"])){

            $email = htmlspecialchars ($_POST["email"]);
            $password = htmlspecialchars ($_POST["password"]);

            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;

            User::check_login_form($email,$password);

            $error = User::check_login_form($email, $password)[0];
            $error_email = User::check_login_form($email, $password)[1];
            $error_password = User::check_login_form($email, $password)[2];

            if(!$error){
                User::action_authorization($email, $password);
            }
        }

        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/user/login.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionFeedback()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/user/feedback.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionCabinet()
    {
        require_once ('/views/layouts/header.php');
        require_once (ROOT.'/views/user/cabinet.php');
        require_once ('/views/layouts/footer.php');
    }

    public function actionCart()
    {
        require_once(ROOT. '/models/Product.php');
        require_once(ROOT. '/models/Admin.php');

        if (isset($_SESSION['cart_product_list'])) {
            $cartData = unserialize($_SESSION['cart_product_list']);
        } else {
            $cartData = [];
        }

        if(isset($_POST["order"])) {
            $cartData = serialize($cartData);
            $current_date = date("m.d.y");
            $current_time = date("H:i");
            if(isset($_SESSION["user_id"])) {
                $user_id = $_SESSION["user_id"];
            }
            else{
                $user_id = "";
            }

            User::make_an_order($cartData,$user_id,$current_date,$current_time);
            header("Location: /cabinet");
        }

        if(isset($_POST["delete_product_from_cart_list"])) {
            $delete_product_id = $_POST["delete_product_id"];
            User::delete_product_from_cart_list($delete_product_id);
        }

        require_once ('/views/layouts/header.php');

        if (isset($_SESSION['cart_product_list'])){
            require_once (ROOT.'/views/user/cart.php');
        }
        else{
        }
        require_once ('/views/layouts/footer.php');
    }


    public function actionCompare()
    {
        require_once(ROOT. '/models/Product.php');
        require_once(ROOT. '/models/Admin.php');
        require_once (ROOT. '/models/User.php');

        if (isset($_SESSION['compare_product_list'])) {
            $compareData = unserialize($_SESSION['compare_product_list']);
        } else {
            $compareData = [];
        }

        if(isset($_POST["delete_product_from_compare_list"])) {
            $delete_product_id = $_POST["delete_product_id"];
            User::delete_product_from_compare_list($delete_product_id);
        }

        if(isset($_POST["add_to_cart"])) {

            if (User::action_check_authorization()==true) {

                $product_id = $_POST["add_to_cart_product_id"];

                $cart_product_list = [];

                if (isset($_SESSION['cart_product_list'])) {
                    $cartData = unserialize($_SESSION['cart_product_list']);
                } else {
                    $cartData = [];
                }

                if (isset($cartData[$product_id])) {
                    $cartData[$product_id]++;
                } else {
                    $cartData[$product_id] = 1;
                }

                $_SESSION['cart_product_list'] = serialize($cartData);

                if(isset($_SESSION['cart_product_amount'])){
                }
                else{
                    $_SESSION['cart_product_amount']=0;
                }
                $_SESSION['cart_product_amount']=$_SESSION['cart_product_amount']+1;
                $_SESSION['compare_product_amount']=$_SESSION['compare_product_amount']-1;

                unset($compareData[$product_id]);
                $_SESSION['compare_product_list']=serialize($compareData);

                if(($_SESSION['compare_product_amount'])<1){
                    unset($_SESSION['compare_product_list']);
                    unset($_SESSION['compare_product_amount']);
                }
            }
            else {
                header("Location: /login");
            }
        }
        require_once ('/views/layouts/header.php');
        if (isset($_SESSION['compare_product_list'])){
            require_once (ROOT.'/views/user/compare.php');
        }
        else{
        }
        require_once ('/views/layouts/footer.php');
    }







}