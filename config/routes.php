<?php


    //возвращает асоциативный массив

    return array(

    '/product/add/' => 'product/add/',
    '/product/add_compare/' => 'product/add_compare/',
    '/product/save_product_review' => 'product/saveProductReview',
    '/product/create_review' => 'product/CreateReview',
    '/product/edit_review' => 'product/EditReview',
    '/product/update_review' => 'product/UpdateReview',
    '/product/delete_review' => 'product/DeleteReview',
    '/product/load_modal_to_select_product_color'=>'product/load_modal_to_select_product_color',
    '/product/save_review_vote'=>'product/SaveReviewVote',
    '/product/delete_review_vote'=>'product/DeleteReviewVote',
    '/product/save_new_review_comment'=>'product/SaveNewReviewComment',
    '/product/show_more_review_comments'=>'product/ShowMoreReviewComments',
    '/product/delete_product_from_compare_list'=>'product/DeleteProductFromCompareList',

    '/$' => 'TopMenu/homepage',
    '/\?(.*)' => 'TopMenu/homepage',

    '/about' => 'TopMenu/about',
    '/contact' => 'TopMenu/contact',
    '/delivery' => 'TopMenu/delivery',

    '/registration' => 'user/registration',
    '/login' => 'user/login',
    '/feedback' => 'user/feedback',

    '/cabinet' => 'user/cabinet/ViewOrderInformation',
    '/cabinet/order/[0-9]{0,4}' => 'user/ViewOrderInformation',
    '/cabinet/orders_list' => 'user/ViewUserOrdersList',

    '/cart' => 'user/cart',
    '/compare' => 'user/compare',
    '/compare_category/(.*)' => 'user/compareCategoryProducts',

    '/admin' => 'admin/cabinet',
    '/no_permission' => 'admin/NoPermission',

//    Управление товарами в панеле администратора
    '/admin/edit_products' => 'adminProduct/editProductsView',
    '/admin/edit_products\?(.*)' => 'adminProduct/editProductsView',

    '/admin/add_product' => 'adminProduct/addProduct',
    '/admin/delete_product/[0-9]{0,4}' => 'adminProduct/deleteProduct',
    '/admin/edit_product/[0-9]{0,4}' => 'adminProduct/editProduct',
    '/admin/load_selected_parameters_to_additional_product_parameters' => 'adminProduct/loadSelectedParametersList',
    '/admin/delete_additional_parameter_from_edit_product' => 'adminProduct/deleteAdditionalParameter',
    '/admin/load_category_parameters_to_add_product' => 'adminProduct/loadCategoryParameters',
    '/admin/test_autocomplete' => 'adminProduct/testAutocomplete',
    '/admin/edit_selected_product/load_new_rows_with_selected_colors' => 'adminProduct/LoadNewRowsWithSelectedColors',

//    Управление категориями в панеле администратора
    '/admin/edit_category' => 'adminCategory/editCategoryView',
    '/admin/edit_selected_category/[0-9]{0,4}' => 'adminCategory/editSelectedCategory',
    '/admin/reload_category_parameters_table' => 'adminCategory/reloadCategoryParametersTable',
    '/admin/save_new_parameter_to_category' => 'adminCategory/saveNewParameterToCategory',
    '/admin/remove_parameter_from_category'=> 'adminCategory/removeParameterFromCategory',
    '/admin/save_selected_existing_parameters_to_category'=> 'adminCategory/saveSelectedExistingParametersToCategory',
    '/admin/update_the_sort_order_of_categories' => 'adminCategory/updateTheSortOrderOfCategories',
    '/admin/update_category_name_using_editable' => 'adminCategory/updateCategoryNameUsingEditable',
    '/admin/update_category_status' => 'adminCategory/updateCategoryStatus',

//    Управление параметрами в панеле администратора
    '/admin/edit_parameters' => 'adminParameter/editParametersView',
    '/admin/edit_selected_parameter/(.*)' => 'adminParameter/editSelectedParameter',
    '/admin/update_selected_parameter' => 'adminParameter/updateSelectedParameter',
    '/admin/remove_selected_parameter' => 'adminParameter/removeSelectedParameter',
    '/admin/save_new_parameter' => 'adminParameter/saveNewParameter',
    '/admin/load_parameters_table' => 'adminParameter/loadParametersTable',

// Управления отзывами в панеле администратора
    '/admin/reviews_control$' => 'adminReview/reviewsControl',
    '/admin/reviews_control\?(.*)' => 'adminReview/reviewsControl',
    '/admin/reviews_control/delete_review' => 'adminReview/deleteReview',

// Управление заказами в панеле администратора

    '/admin/orders_control' => 'adminOrder/OrdersControl',
    '/admin/order/[0-9]{0,4}' => 'adminOrder/ViewOrderInformation',
    '/admin/delete_order' => 'adminOrder/DeleteSelectedOrder',

    '/product/[0-9]{0,4}' => 'product/view',

    '/category/delete_filter_tag' => 'category/DeleteFilterTag',
    '/category/(.*)' => 'category/view',

    '/search/(.*)' => 'search/viewSearchResult'
        

);

?>