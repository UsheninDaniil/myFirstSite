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
require_once('/components/Helper.php');
require_once('/controllers/AdminController.php');


class AdminReviewController
{
    public function actionReviewsControl()
    {
        require_once('/components/Pagination.php');

        Admin::check_if_administrator();

        // настройки пагинации, которые влияют на ее отображение
        $pagination = new Pagination(10);

        $filter_parameters = Helper::get_get_parameters_from_url_without_page();

        if (!empty ($filter_parameters)) {
            $get_elements_request = AdminReview::build_request_for_filter($filter_parameters);
        }

        if(empty($get_elements_request)){
            $get_elements_request = "SELECT * FROM product_reviews";
        }

        $get_total_elements_amount_request = "SELECT COUNT(*) FROM($get_elements_request) tmp";

        $review_list = $pagination->get_pagination_elements($get_elements_request, $get_total_elements_amount_request);

        if(isset($_GET['user_id'])){
            $search_user_id = $_GET['user_id'];
        }
        if(isset($_GET['product_id'])){
            $search_product_id = $_GET['product_id'];
        }
        if(isset($_GET['rating'])){
            $search_rating = $_GET['rating'];
        }

        require_once('/views/layouts/header.php');
        require_once('/views/admin/review/review_control.php');
        require_once('/views/layouts/footer.php');
    }

    public function actionDeleteReview()
    {
        Admin::check_if_administrator();

        if (isset($_POST['review_id'])) {
            $review_id = $_POST['review_id'];
            AdminReview::delete_review_by_id($review_id);
        }
    }
}