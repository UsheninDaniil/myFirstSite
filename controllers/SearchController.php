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

class SearchController
{
    public function actionViewSearchResult(){

        if(isset($_GET['text'])){
            $search_query = $_GET['text'];
        }

        $categoryList = Category::get_category_list();
        $productList = Search::get_product_list_for_search($search_query);

        require_once(ROOT. '/views/layouts/header.php');
        require_once(ROOT. '/views/search/search_view.php');
        require_once(ROOT. '/views/layouts/footer.php');
    }
}