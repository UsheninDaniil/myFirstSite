<?php


    //возвращает асоциативный массив

    return array(


    'product/add/([0-9]+)' => 'product/add/',
    'product/add_compare/([0-9]+)' => 'product/add_compare/',

    '/$' => 'TopMenu/homepage',
    '/page=(.*)' => 'TopMenu/homepage',

    '/about' => 'TopMenu/about',
    '/contact' => 'TopMenu/contact',
    '/delivery' => 'TopMenu/delivery',

    '/registration' => 'user/registration',
    '/login' => 'user/login',
    '/feedback' => 'user/feedback',
    '/cabinet' => 'user/cabinet',
    '/cart' => 'user/cart',
    '/compare' => 'user/compare',
    '/compare_category/(.*)' => 'user/compareCategoryProducts',

    '/admin' => 'admin/cabinet',
    '/no_permission' => 'admin/NoPermission',

//    Управление товарами в панеле администратора
    '/admin/edit_products' => 'adminProduct/editProductsView',
    '/admin/add_product' => 'adminProduct/addProduct',
    '/delete_product/[0-9]{0,4}' => 'adminProduct/deleteProduct',
    '/edit_product/[0-9]{0,4}' => 'adminProduct/editProduct',
    '/load_selected_parameters_to_additional_product_parameters' => 'adminProduct/loadSelectedParametersList',
    '/admin/delete_additional_parameter_from_edit_product/(.*)/(.*)' => 'adminProduct/deleteAdditionalParameter',
    '/admin/load_category_parameters_to_add_product/(.*)' => 'adminProduct/loadCategoryParameters',
    '/admin/apply_multi_select_filter_for_product_list' => 'adminProduct/applyMultiSelectFilterForProductList',

//    Управление категориями в панеле администратора
    '/admin/edit_category' => 'adminCategory/editCategoryView',
    '/admin/edit_selected_category/[0-9]{0,4}' => 'adminCategory/editSelectedCategory',
    '/admin/reload_category_parameters_table/[0-9]{0,4}' => 'adminCategory/reloadCategoryParametersTable',
    '/admin/save_new_parameter_to_category/(.*)' => 'adminCategory/saveNewParameterToCategory',
    '/admin/remove_parameter_from_category/(.*)/(.*)'=> 'adminCategory/removeParameterFromCategory',
    '/admin/save_selected_existing_parameters_to_category/(.*)'=> 'adminCategory/saveSelectedExistingParametersToCategory',
    '/admin/change_the_sort_order_of_categories' => 'adminCategory/changeTheSortOrderOfCategories',
    '/admin/update_category_name_using_editable' => 'adminCategory/updateCategoryNameUsingEditable',
    '/admin/update_category_status_using_editable' => 'adminCategory/updateCategoryStatusUsingEditable',

//    Управление параметрами в панеле администратора
    '/admin/edit_parameters' => 'adminParameter/editParametersView',
    '/admin/edit_selected_parameter/(.*)' => 'adminParameter/editSelectedParameter',
    '/admin/update_selected_parameter/(.*)' => 'adminParameter/updateSelectedParameter',
    '/admin/remove_selected_parameter/(.*)' => 'adminParameter/removeSelectedParameter',
    '/admin/save_new_parameter' => 'adminParameter/saveNewParameter',
    '/admin/load_parameters_table' => 'adminParameter/loadParametersTable',

    '/product/[0-9]{0,4}' => 'product/view',
    '/category/(.*)' => 'category/view',
    '/category/(.*)/page=(.*)' => 'category/view',

    '/search/(.*)' => 'search/viewSearchResult'
        

);

?>