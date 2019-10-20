<a href ="/cart" class="cart-count" style="color: black">
    <?php
    if(isset($_SESSION['cart_product_amount'])){
        $cart_product_amount = $_SESSION['cart_product_amount'];
        echo "<i class='fas fa-shopping-cart'></i><span> Корзина</span>($cart_product_amount)";
    }
    else {
        echo "<i class='fas fa-shopping-cart'></i><span> Корзина</span>";
    }
    ?>
</a>