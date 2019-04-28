



<script src="/template/jquery-3.3.1.min.js"></script>
<script src="/template/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>



<!--Управление корзиной-->
<script>
    $(document).ready(function(){
        $(".add-to-cart").click(function (event) {
            event.preventDefault();

            var id = $(this).data("id");
            $.post("/product/add/"+id, {}, function (data) {
                $(".cart-count").html(data);
                $(".check-in-the-cart"+id).html("<span class=\"glyphicon glyphicon-check\"></span>");
            },"html"
            );
        });

        $(".add-to-compare").click(function (event) {
            event.preventDefault();

            var id = $(this).data("id");
            $.post("/product/add_compare/"+id, {}, function (data) {
                $(".compare-count").html(data);
                $(".check-in-the-compare"+id).html("<span class=\"glyphicon glyphicon-check\"></span>");
            },"html"
            );
        });
    });
</script>

<!--Управление категориями в панеле администратора-->
<script>

    function edit_category() {
        var e = document.getElementById("product_category_id");
        category_id = e.options[e.selectedIndex].value;

        $.post("/admin/edit_selected_category/"+category_id, {}, function (data) {
                $(".replace").html(data);
            },"html"
        );
    }

    $(document).on('click', '.load_existing_parameters_form', function (event)
    {
        event.preventDefault();
        $("#load_existing_parameters_form").show();
        $("#hide_load_existing_parameters_form_button").show();
    });

    $(document).on('click', '.hide_load_existing_parameters_form_button', function (event) {
        event.preventDefault();
        $("#load_existing_parameters_form").hide();
        $("#hide_load_existing_parameters_form_button").hide();
    });

    $(document).on('click', '.load_new_parameter_form', function (event)
    {
        event.preventDefault();
        $("#load_new_parameter_form").show();
        $("#hide_load_new_parameter_form_button").show();
    });

    $(document).on('click', '.hide_load_new_parameter_form_button', function (event) {
        event.preventDefault();
        $("#load_new_parameter_form").hide();
        $("#hide_load_new_parameter_form_button").hide();
    });

    $(document).on('click', '.save_new_parameter_to_category', function (event)
    {
        event.preventDefault();

        var form = $('#create_new_parameter_information');
        var value1 = form.find('input[name="parameter_name"]').val();
        var value2 = form.find('input[name="parameter_russian_name"]').val();
        var value3 = form.find('input[name="parameter_unit"]').val();
        var new_parameter_information={parameter_name:value1, parameter_russian_name:value2, parameter_unit:value3};
        var category_id = $(this).parents(".parameter_list_table").data("categoryId");

        $.post("/admin/save_new_parameter_to_category/"+category_id, new_parameter_information , function (data) {
                $(".remove_info").html(data);
            }, "html"
        );

        $.post("/admin/edit_selected_category/"+category_id, {}, function (data) {
                $(".replace").html(data);
            },"html"
        );
    });

    $(document).on('click', '.remove_parameter_from_category', function (event) {
        event.preventDefault();

        var remove_parameter_id = $(this).data("parameterId");
        var category_id = $(this).parents(".parameter_list_table").data("categoryId");

        $.post("/admin/remove_parameter_from_category/"+remove_parameter_id+"/"+category_id, {}, function (data) {
            $(".remove_info").html(data);
            }, "html"
        );

        $.post("/admin/edit_selected_category/"+category_id, {}, function (data) {
                $(".replace").html(data);
            },"html"
        );
    });

    $(document).on('click', '.save_selected_existing_parameters_to_category', function (event) {
        event.preventDefault();

        var form = $('#save_parameters_list');
        var parameters_list_data = form.serialize();
        var category_id = $(this).parents(".parameter_list_table").data("categoryId");

        $.post("/admin/save_selected_existing_parameters_to_category/"+category_id, parameters_list_data, function (data) {
                $(".remove_info").html(data);
            }, "html"
        );

        $.post("/admin/edit_selected_category/"+category_id, {}, function (data) {
                $(".replace").html(data);
            },"html"
        );
    });

</script>

<!--Управление параметрами в панеле администратора-->
<script>

    $(document).on('click', '.load_new_parameter_form_2', function (event)
    {
        event.preventDefault();
        $("#load_new_parameter_form_2").show();
        $("#hide_load_new_parameter_form_button_2").show();

    });

    $(document).on('click', '.hide_load_new_parameter_form_button_2', function (event) {
        event.preventDefault();
        $("#load_new_parameter_form_2").hide();
        $("#hide_load_new_parameter_form_button_2").hide();
    });

    $(document).on('click', '.parameters_list_table .remove_selected_parameter', function (event) {
        event.preventDefault();
        var remove_parameter_id = $(this).data("parameterId");

        if (confirm("Вы действительно хотите удалить параметер #remove_parameter_id ?")){
            $.post("/admin/remove_selected_parameter/"+remove_parameter_id, {}, function (data) {
                    $(".parameters_list_table").html(data);
                },"html"
            );
        }

    });

    $(document).on('click', '.parameters_list_table .save_new_parameter', function (event)
    {
        event.preventDefault();

        var form = $('#create_new_parameter_information_2');
        var value1 = form.find('input[name="parameter_name"]').val();
        var value2 = form.find('input[name="parameter_russian_name"]').val();
        var value3 = form.find('input[name="parameter_unit"]').val();
        var new_parameter_information={parameter_name:value1, parameter_russian_name:value2, parameter_unit:value3};
        var category_id = $(this).parents(".parameter_list_table").data("categoryId");

        $.post("/admin/save_new_parameter", new_parameter_information , function (data) {
            $(".check_result").html(data);
            }, "html"
        );

        $.post("/admin/load_parameters_table", {}, function (data) {
                $(".parameters_list_table").html(data);
            },"html"
        );
    });

    $(document).on('click', '.update_selected_parameter_information', function (event) {
        event.preventDefault();

        var parameter_id = $(this).data("parameterId");
        var form = $('#update_selected_parameter_information');
        var value1 = form.find('input[name="update_parameter_name"]').val();
        var value2 = form.find('input[name="update_parameter_russian_name"]').val();
        var value3 = form.find('input[name="update_parameter_unit"]').val();
        var new_parameter_information = {parameter_name: value1, parameter_russian_name: value2, parameter_unit: value3};

        $.post("/admin/update_selected_parameter/" + parameter_id, new_parameter_information, function (data) {
                document.location.href = "/admin/edit_parameters";
            }, "html"
        );
    });

</script>

<!--Управление товарами в панеле администратора-->
<script>

    $(document).on('click', 'form#edit_product a#load_existing_parameter', function (event)
    {
        event.preventDefault();
        $(".existing_parameters_form").show();
        $("form#edit_product .hide_button").show();
    });

    $(document).on('click', 'form#edit_product .hide_button', function (event) {
        event.preventDefault();
        $(".existing_parameters_form").hide();
        $(this).hide();
    });

    $(document).on('click', 'form#edit_product .existing_parameters_form a#add_selected_parameters', function (event) {
        event.preventDefault();

        var form = $('form#edit_product ');
        console.log(form);
        var parameters_list_data = form.serialize();
        console.log(parameters_list_data);

        $.post("/admin/load_selected_parameters_to_additional_product_parameters", parameters_list_data, function (data) {
                $('form#edit_product .additional_parameters .new_parameters').html(data);
                $(".existing_parameters_form").hide();
                $("form#edit_product .hide_button").hide();
            }, "html"
        );
    });

    $(document).on('click', 'form#edit_product a.remove_additional_parameter', function (event)
    {
        event.preventDefault();

        var product_id = $('form#edit_product').data("productId");
        var parameter_id = $(this).data("parameterId");

        $.post("/admin/delete_additional_parameter_from_edit_product/"+ product_id + "/" + parameter_id, {}, function (data) {
            document.location.href = "/admin/edit_product/" + product_id;
            }, "html"
        );

    });

    function load_category_parameters() {
        var form = document.getElementById("load_category_parameters_to_add_product");
        category_id = form.options[form.selectedIndex].value;

        $.post("/admin/load_category_parameters_to_add_product/"+category_id, {}, function (data) {
                // $("form#add_product .category_parameters_list").html(data);
                $("#category_parameters_list").html(data);
            },"html"
        );
    }



</script>


<script>

$( function() {

    $("#accordion > div").accordion({
        header: "h4",
        collapsible: true,
        heightStyle:"content",
        icons: false

    });
} );

</script>

</body>
</html>

