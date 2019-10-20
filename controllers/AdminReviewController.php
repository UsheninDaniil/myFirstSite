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
require_once('/controllers/AdminController.php');


class AdminReviewController
{
    public function actionReviewsControl()
    {
        require_once('/components/Pagination.php');

        Admin::check_if_administrator();

        $review_list = AdminReview::get_review_list();

        if (!empty($_GET)) {
            $get_parameters_array = $_GET;
            if (isset($get_parameters_array['page'])) {
                unset($get_parameters_array['page']);
            }
            $get_parameters_without_page = $get_parameters_array;
        }

        $get_products_without_filter = false;

        if (!empty($get_parameters_without_page)) {

            $united_request = '';

            foreach ($get_parameters_without_page as $parameter_name => $parameter_content) {

                if (gettype($parameter_content) == "array") {

                    $parameter_values_array = $parameter_content;

                    if (!empty($parameter_values_array)) {
                        $request_first_part = "SELECT * FROM product_reviews WHERE ";
                        $request_second_part = "$parameter_name  IN (" . implode(',', $parameter_values_array) . ")";

                        if (strlen($united_request) < 1) {
                            $united_request = $united_request . $request_first_part . $request_second_part;
                        } else {
                            $united_request = $united_request . ' AND ' . $request_second_part;
                        }
                    }
                } else {
                    $parameter_value = $parameter_content;
                    if (!empty($parameter_value)) {
                        $request_first_part = "SELECT * FROM product_reviews WHERE ";

                        if ($parameter_name === "product_id") {
                            $request_second_part = "$parameter_name = '$parameter_value'";
                        }

                        if ($parameter_name === "user_id") {
                            $request_second_part = "$parameter_name = '$parameter_value'";
                        }

                        if ($parameter_name === "rating") {
                            $request_second_part = "$parameter_name = '$parameter_value'";
                        }


                        if ((strlen($united_request) < 1) && (!empty($request_second_part))) {
                            $united_request = $united_request . $request_first_part . $request_second_part;
                        } else {
                            $united_request = $united_request . ' AND ' . $request_second_part;
                        }
                    }
                }

            }

            if (!empty($united_request)) {

                $pagination = new Pagination();

                if (isset($_GET['page'])) {
                    $current_page_number = $_GET['page'];
                } else {
                    $current_page_number = 1;
                }

                $amount_of_elements_on_page = 1;
                $get_total_elements_amount_request = "SELECT COUNT(*) FROM($united_request) tmp";

                $result_parameters = $pagination->get_pagination_parameters($current_page_number, $amount_of_elements_on_page, $get_total_elements_amount_request);

                $total_count = $result_parameters['total_count'];
                $start = $result_parameters['start'];

                $get_elements_request = "$united_request";

                $review_list = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

                $limit = 4;

            } else {
                $get_products_without_filter = true;
            }

        } else {
            $get_products_without_filter = true;
        }

        if ($get_products_without_filter == true) {

            $pagination = new Pagination();

            $amount_of_elements_on_page = 10;

            if (isset($_GET['page'])) {
                $current_page_number = $_GET['page'];
            } else {
                $current_page_number = 1;
            }

            $get_total_elements_amount_request = "SELECT COUNT(*) FROM product_reviews";

            $result_parameters = $pagination->get_pagination_parameters($current_page_number, $amount_of_elements_on_page, $get_total_elements_amount_request);

            $total_count = $result_parameters['total_count'];
            $start = $result_parameters['start'];

            $get_elements_request = "SELECT * FROM product_reviews";

            $review_list = $pagination->get_pagination_elements($start, $amount_of_elements_on_page, $get_elements_request);

            $limit = 4;
        }

        if (empty($review_list)) {
            $current_page_number = 0;
        }

//        if(!empty($united_request)){
//            echo "united_request <br/>".$united_request;
//        }

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