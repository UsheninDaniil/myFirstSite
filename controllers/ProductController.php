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


Class ProductController
{

    public function actionView()
    {
        $product_id = Product::get_product_id();

        $product_info = Product::get_product_by_id($product_id);
        $product_parameters_info = Product::get_product_parameters_by_id($product_id);

        $categoryList = Category::get_category_list();

        $category_id = Category::get_category_id_by_product_id($product_id);

        $productList = Product::get_product_list_by_category_id($category_id, 1, 10);

        $product_review_list = Product::get_product_review_list_by_product_id($product_id);

        $product_colors = Color::get_product_colors($product_id);


        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $current_user_review = Product::get_product_review_by_product_id_and_user_id($product_id, $user_id);

            if (Product::check_review_exist($product_id, $user_id) === true) {
                $review_exists = true;
            } else {
                $review_exists = false;
            }

            $review_likes_list_of_current_user = Product::get_likes_list_by_user_id_and_product_id($user_id, $product_id);
            $is_user_logged_in = 'yes';
        } else {
            $is_user_logged_in = 'no';
        }

        require_once('/views/layouts/header.php');
        require_once('/views/product/view.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionReviewCrud()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', $uri);
        $action = $segments[3];

        if ($action === "create") {

            if (isset($_POST)) {
                $text_review = htmlspecialchars($_POST["text_review"]);
                $rating = htmlspecialchars($_POST["rating"]);
                $product_id = htmlspecialchars($_POST["product_id"]);

                if (isset($_SESSION["user_id"])) {

                    $user_id = $_SESSION["user_id"];
                    if (Product::check_review_exist($product_id, $user_id) === false) {
                        $current_date = date("d.m.y");
                        $current_time = date("H:i");

                        $review_information['product_id'] = $product_id;
                        $review_information['user_id'] = $user_id;
                        $review_information['text_review'] = $text_review;
                        $review_information['rating'] = $rating;
                        $review_information['date'] = $current_date;
                        $review_information['time'] = $current_time;

                        Product::save_review($review_information);
                    }
                }
            }
        }

//        $this ->actionAdd();

        if ($action === "edit") {

            $user_id = $_SESSION["user_id"];

            $product_id = $_POST['product_id'];

            $current_user_review = Product::get_product_review_by_product_id_and_user_id($product_id, $user_id);

            $review_rating = '
                <div class="rating_star_container" data-product-id="' . $product_id . '" >

                    <div data-rating="1" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="2" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="3" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="4" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                    <div data-rating="5" class="rating_star">
                        <i class="fa-star far"></i>
                        <i class="fa-star fas"></i>
                    </div>

                </div>
                ';


            $review = $current_user_review['review'];
            $review_text = "<form><textarea id='text_review_update'>$review</textarea></form>";

            $result = json_encode(['stars' => $review_rating, 'text' => $review_text]);

            echo $result;


        }

        if ($action === "update") {

            if (isset($_POST)) {

                $text_review = htmlspecialchars($_POST["text_review"]);
                $rating = htmlspecialchars($_POST["rating"]);
                $product_id = htmlspecialchars($_POST["product_id"]);

                if (isset($_SESSION["user_id"])) {

                    $user_id = $_SESSION["user_id"];
                    if (Product::check_review_exist($product_id, $user_id) === true) {

                        $current_date = date("d.m.y");
                        $current_time = date("H:i");

                        $review_information['product_id'] = $product_id;
                        $review_information['user_id'] = $user_id;
                        $review_information['text_review'] = $text_review;
                        $review_information['rating'] = $rating;
                        $review_information['date'] = $current_date;
                        $review_information['time'] = $current_time;

                        Product::update_review($review_information);
                    }
                }
            }

        }

        if ($action === "delete") {

            if (isset($_POST)) {

                $product_id = $_POST['product_id'];

                if (isset($_SESSION["user_id"])) {

                    $user_id = $_SESSION["user_id"];
                    if (Product::check_review_exist($product_id, $user_id) === true) {
                        Product::delete_review($product_id, $user_id);
                    }
                }
            }
        }

    }

    public function actionAdd()
    {
        if (isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            $product_color = $_POST['color_id'];
            $product_amount = $_POST['product_amount'];

            if (isset($_SESSION['cart_product_list'])) {
                $cartData = unserialize($_SESSION['cart_product_list']);
            } else {
                $cartData = [];
            }

            if (isset($cartData[$product_id][$product_color])) {
                $cartData[$product_id][$product_color] += $product_amount;
            } else {
                $cartData[$product_id][$product_color] = $product_amount;
            }

            $_SESSION['cart_product_list'] = serialize($cartData);

            if (isset($_SESSION['cart_product_list'])) {
                $cartData = unserialize($_SESSION['cart_product_list']);
                $cart_product_amount = 0;
                foreach ($cartData as $product_id => $information) {
                    foreach ($information as $color => $amount)
                        $cart_product_amount = $cart_product_amount + $amount;
                }
            }

            $_SESSION['cart_product_amount'] = $cart_product_amount;

            require_once(ROOT . '/views/layouts/cart_template.php');
        }
    }


    public function actionAdd_compare()
    {
        if (isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];

            $category_id = Category::get_category_id_by_product_id($product_id);

            $cart_product_list = [];

            if (isset($_SESSION['compare_product_list'])) {
                $compareData = unserialize($_SESSION['compare_product_list']);
            } else {
                $compareData = [];
            }

            if (isset($compareData[$category_id])) {

                if (in_array($product_id, $compareData[$category_id])) {
                } else {
                    array_push($compareData[$category_id], $product_id);
                }
            } else {
                $compareData[$category_id] = array();
                array_push($compareData[$category_id], $product_id);
            }

            $_SESSION['compare_product_list'] = serialize($compareData);

            if (isset($_SESSION['compare_product_list'])) {
                $compareData = unserialize($_SESSION['compare_product_list']);
                $compare_product_amount = count($compareData, COUNT_RECURSIVE) - count($compareData);
            }

            $_SESSION['compare_product_amount'] = $compare_product_amount;

            require_once('/views/layouts/compare_template.php');

        }
    }

    public function actionLoad_modal_to_select_product_color()
    {
        $product_id = $_POST['product_id'];
        $ability_to_choose_the_color = $_POST['ability_to_choose_the_color'];

        $product_info = Product::get_product_by_id($product_id);
        $product_colors = Color::get_product_colors($product_id);

        require_once('/views/product/select_product_color_modal_body.php');
    }

    public function actionSaveReviewVote()
    {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            return;
        }

        $review_id = $_POST['review_id'];
        $vote = $_POST['vote'];

        if ($vote === 'true') {
            $vote = 1;
        } else {
            $vote = 0;
        }

        Product::save_review_vote($review_id, $user_id, $vote);
    }


    public function actionDeleteReviewVote()
    {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            return;
        }

        $review_id = $_POST['review_id'];
        $vote = $_POST['vote'];

        if ($vote === 'true') {
            $vote = 1;
        } elseif ($vote === 'false') {
            $vote = 0;
        }

        Product::delete_review_vote($review_id, $user_id, $vote);
    }

    public function actionSaveNewReviewComment()
    {
        if (isset($_SESSION["user_id"], $_POST)) {
            $user_id = $_SESSION["user_id"];
            $text = $_POST['text'];
            $review_id = $_POST['review_id'];
            $date = date("d.m.y");
            $time = date("H:i");

            Product::save_review_comment($user_id, $review_id, $text, $date, $time);

            $user_name = User::get_user_name_by_user_id($user_id);

            require('/views/product/review_comment_template.php');
        }
    }

    public function actionShowMoreReviewComments()
    {
        if (!isset($_POST)) {
            return;
        }

        $review_comments_information = $_POST['review_comments_information'];

        $review_id = $review_comments_information['review_id'];
        $already_loaded = $review_comments_information['already_loaded'];
        $amount_to_load = $review_comments_information['amount_to_load'];

        $review_comments = Product::get_review_comments_range($review_id, $already_loaded, $amount_to_load);

        foreach ($review_comments as $comment) {
            $text = $comment['comment'];
            $user_id = $comment['user_id'];
            $date = $comment['date'];
            $user_name = User::get_user_name_by_user_id($user_id);
            require('/views/product/review_comment_template.php');
        }
    }


    public static function actionDeleteProductFromCompareList()
    {
        if (isset($_POST['product_id'], $_POST['category_id'])) {
            $product_id = $_POST['product_id'];
            $category_id = $_POST['category_id'];
            User::delete_product_from_compare_list($product_id, $category_id);
        }

    }


}


?>