<!--Управление корзиной-->

$(document).ready(function(){
    $(".add-to-cart").click(function (event) {
        event.preventDefault();

        var id = $(this).data("id");
        $.post("/product/add/"+id, {}, function (data) {
                $(".cart-count").html(data);
                $(".check-in-the-cart"+id).html("<i class='far fa-check-square'></i>");
            },"html"
        );
    });

    $(".add-to-compare").click(function (event) {
        event.preventDefault();

        var id = $(this).data("id");
        $.post("/product/add_compare/"+id, {}, function (data) {
                $(".compare-count").html(data);
                $(".check-in-the-compare"+id).html("<i class='far fa-check-square'></i>");
            },"html"
        );
    });



});