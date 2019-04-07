<?php
session_start();
//шаблон
$cart_product_list = ['product_id' => 'count'];

//пример
$cart_product_list = [
    35 => 5,
    15 => 1,
    28 => 10,
];
$_SESSION['cart_product_list'] = serialize($cart_product_list);

if (isset($_SESSION['cart_product_list'])) {
    $cartData = unserialize($_SESSION['cart_product_list']);
} else {
    $cartData = [];
}

if (isset($cartData[15])) {
    $cartData[15]++;
} else {
    $cartData[15] = 1;
}

$_SESSION['cart_product_list'] = serialize($cartData);

$newCartData = unserialize($_SESSION['cart_product_list']);

