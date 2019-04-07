<?php


    //возвращает асоциативный массив

    return array(


    'product/add/([0-9]+)' => 'product/add/',
    'product/add_compare/([0-9]+)' => 'product/add_compare/',

    '/$' => 'TopMenu/homepage',

    '/about' => 'TopMenu/about',
    '/contact' => 'TopMenu/contact',
    '/delivery' => 'TopMenu/delivery',

    '/registration' => 'user/registration',
    '/login' => 'user/login',
    '/feedback' => 'user/feedback',
    '/cabinet' => 'user/cabinet',
    '/cart' => 'user/cart',
    '/compare' => 'user/compare',

    '/admin' => 'admin/cabinet',
    '/no_permission' => 'admin/NoPermission',

    '/admin/add' => 'admin/addProduct',
    '/delete/[0-9]{0,4}' => 'admin/deleteProduct',
    '/edit/[0-9]{0,4}' => 'admin/editProduct',

    '/admin/edit_category' => 'admin/editCategory',
    '/admin/edit_category/[0-9]{0,4}' => 'admin/editCategoryParameters',

    '/admin/load_existing_parameters' => 'admin/loadExistingParameters',
    '/admin/create_new_parameter'=> 'admin/createNewParameter',
    '/admin/save_new_parameter/(.*)' => 'admin/saveNewParameter',
    '/admin/remove_parameter/(.*)/(.*)'=> 'admin/removeParameter',
    '/admin/save_selected_existing_parameters/(.*)'=> 'admin/saveSelectedExistingParameters',



    '/product/[0-9]{0,4}' => 'product/view',

    '/category/[0-9]{0,4}' => 'category/view'
        

);

?>