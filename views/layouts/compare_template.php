<a href ="/compare" class="compare-count" style="color: black">
    <?php
    if(isset($_SESSION['compare_product_amount'])){
        $compare_product_amount = $_SESSION['compare_product_amount'];
        echo "<i class='fas fa-balance-scale'></i><span> Сравнение</span> ($compare_product_amount)";
    }
    else {
        echo "<i class='fas fa-balance-scale'></i><span> Сравнение</span>";
    }
    ?>
</a>