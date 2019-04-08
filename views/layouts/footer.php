



<script src="/template/jquery-3.3.1.min.js"></script>

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

<script>

    function edit_category() {

        // var selected = document.getElementById('product_category_id');
        // category_id = document.getElementById(selected.value).innerHTML;

        // $(".replace").load('/admin/edit_category/1');

        var e = document.getElementById("product_category_id");
        category_id = e.options[e.selectedIndex].value;

        $.post("/admin/edit_category/"+category_id, {}, function (data) {
                $(".replace").html(data);
            },"html"
        );
    }

    $(document).on('click', '.load_existing_parameters', function (event)
    {
        event.preventDefault();

        $.post("/admin/load_existing_parameters", {}, function (data) {
                $(".existing-parameters").html(data);
            }, "html"
        );
    });


    $(document).on('click', '.load_existing_parameters', function (event)
    {
        event.preventDefault();

        $.post("/admin/load_existing_parameters", {}, function (data) {
                $(".load_existing_parameters_result").html(data);
            }, "html"
        );
    });

    $(document).on('click', '.create_new_parameter', function (event)
    {
        event.preventDefault();

        $.post("/admin/create_new_parameter", {}, function (data) {
                $(".create_new_parameter_result").html(data);
            }, "html"
        );
    });

    $(document).on('click', '.save_new_parameter', function (event)
    {
        event.preventDefault();

        var form = $('#create_new_parameter_information');

        var value1 = form.find('input[name="parameter_name"]').val();
        var value2 = form.find('input[name="parameter_russian_name"]').val();
        var new_parameter_information={parameter_name:value1, parameter_russian_name:value2};


        var category_id = $(this).parents(".parameter_list_table").data("categoryId");
        // alert(parameters_list);

        $.post("/admin/save_new_parameter/"+category_id, new_parameter_information , function (data) {
                $(".remove_info").html(data);
            }, "html"
        );

        console.log(parameters_list);
    });

    $(document).on('click', '.remove_parameter', function (event) {
        event.preventDefault();

        var remove_parameter_id = $(this).data("parameterId");
        var category_id = $(this).parents(".parameter_list_table").data("categoryId");

        alert("нажато удаление параметра");

        $.post("/admin/remove_parameter/"+remove_parameter_id+"/"+category_id, {}, function (data) {
            $(".remove_info").html(data);
            }, "html"
        );
    });


    $(document).on('click', '.save_selected_existing_parameters', function (event) {
        event.preventDefault();

        var form = $('#save_parameters_list');

        // var value1 = form.find('input[name="name1"]').val();
        // var value2 = form.find('input[name="name2"]').val();
        // var new_parameter_data={name1:value1, name2:value2};

        var parameters_list_data = form.serialize();

        console.log("Данные формы");
        console.log(parameters_list_data);

        var category_id = $(this).parents(".parameter_list_table").data("categoryId");

        alert("нажато сохранение параметра");

        $.post("/admin/save_selected_existing_parameters/"+category_id, parameters_list_data, function (data) {
                $(".remove_info").html(data);
            }, "html"
        );
    });






















</script>



</body>
</html>

