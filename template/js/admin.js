


<!--Управление категориями в панеле администратора-->


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

    $.post("/admin/reload_category_parameters_table/"+category_id, {}, function (data) {
            $(".category_parameters_table").html(data);
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

    $.post("/admin/reload_category_parameters_table/"+category_id, {}, function (data) {
            $(".category_parameters_table").html(data);
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

    $.post("/admin/reload_category_parameters_table/"+category_id, {}, function (data) {
            $(".category_parameters_table").html(data);
        },"html"
    );
});



<!--Управление параметрами в панеле администратора-->


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



<!--Управление товарами в панеле администратора-->


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

$(document).on('change', '#load_category_parameters_to_add_product', function (event){

    var form = document.getElementById("load_category_parameters_to_add_product");
    category_id = form.options[form.selectedIndex].value;

    $.post("/admin/load_category_parameters_to_add_product/"+category_id, {}, function (data) {
            // $("form#add_product .category_parameters_list").html(data);
            $("#category_parameters_list").html(data);

            $( ".tags" ).autocomplete({
                source: function( request, response ) {
                    // Fetch data
                    $.ajax({
                        url: "/admin/test_autocomplete",
                        type: 'post',
                        dataType: "json",
                        data: {
                            search: request.term,
                            parameter_id: $(this.element).data('parameterId')
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                }
            });

        },"html"
    );


});


$(function() {

if($('#accordion  > div').length>0){

    $("#accordion > div").accordion({
        header: "h4",
        collapsible: true,
        heightStyle:"content",
        icons: false
    });

    //turn to inline mode
    $.fn.editable.defaults.mode = 'inline'; // popup default
}

if($('#sortable_categories_list_table .category_name').length > 0){

    $('#sortable_categories_list_table .category_name').on('shown', function(e, editable) {
        changeEditableInputWidth();
    });

    $('#sortable_categories_list_table .category_name').editable({
        name: 'category_name',
        type: 'text',
        url: '/admin/update_category_name_using_editable',
        title: 'Enter username',
        // original-title:'Enter username'
    });
}

} );

// Sortable categories table

$(function() {

    var root = $('#sortable_categories_list_table tbody');

    if(root.length > 0){

    root.children().each(function (index) {

        var category_id = $(this).data("categoryId");

        this.id = 'category_id_list-' + category_id;

    });

    root.sortable({

        containment: "parent",

        'helper': function(e, tr)
        {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },


        'update': function (event, ui) {
            var order = $(this).sortable('serialize');

            $(this).children().each(function(index) {
                $(this).find('#sort_order').html(index + 1)
            });

            $.post("/admin/change_the_sort_order_of_categories", order, function (data) {
                    $(".information").html(data);
                }, "html"
            );
        }
    });

    }
});








$(function() {

    var root = $('.test_container');

    if(root.length > 0){

        // root.children().each(function (index) {
        //
        //     var category_id = $(this).data("categoryId");
        //
        //     this.id = 'category_id_list-' + category_id;
        //
        // });

        root.sortable({

            containment: "parent",

            // 'helper': function(e, tr)
            // {
            //     var $originals = tr.children();
            //     var $helper = tr.clone();
            //     $helper.children().each(function(index)
            //     {
            //         // Set helper cell sizes to match the original sizes
            //         $(this).width($originals.eq(index).width());
            //     });
            //     return $helper;
            // },
            //
            //
            // 'update': function (event, ui) {
            //     var order = $(this).sortable('serialize');
            //
            //     $(this).children().each(function(index) {
            //         $(this).find('#sort_order').html(index + 1)
            //     });
            //
            //     $.post("/admin/change_the_sort_order_of_categories", order, function (data) {
            //             $(".information").html(data);
            //         }, "html"
            //     );
            // }
        });

    }
});








function changeEditableInputWidth(){
    document.getElementById('information').style.border = '1px solid red';
    document.getElementById('information').style.border = '1px solid red';

    var width = $(".cell_with_category_name").eq(0).outerWidth();

    var new_width = width - 67 - 7 - 10 - 14;

    $('.form-control-sm').width(new_width-34);

    console.log(width);
    console.log(new_width);
}


$(function(){

if($(".category_multiselect").length > 0){

    $(".category_multiselect").multiselect({
        header: false,
        noneSelectedText: 'Выберите категории',
        selectedText: 'Выберите категории',
    });

}

if($(".product_status").length > 0){

    $(".product_status").multiselect({
        header: false,
        noneSelectedText: 'Выберите статус',
        selectedText: 'Выберите статус',
    });

}

});

$(function() {
    if($('[name=tags]').length>0){
        $('[name=tags]').tagify();
    }
});

$(document).on('click', '.admin_product_filter_tags tag x', function (e) {

    var tag_name = $(this).next().text();
    tag_name = $.trim(tag_name);

    if((tag_name.indexOf('name')) !== -1){
        $("#search_name").removeAttr("value");
    }

    if((tag_name.indexOf('id')) !== -1){
        $("#search_id").removeAttr("value");
    }

    console.log('tag_name \n' + tag_name);

    $("#select_category option:contains('" + tag_name + "')").removeAttr("selected");
    $("#select_status option:contains('" + tag_name + "')").removeAttr("selected");

    console.log("#select_category option:contains('" + tag_name + "')");

    var form = $('#admin_product_multiselect_form');
    var filter_parameters = form.serialize();

    console.log('filter parameters \n' + filter_parameters);

    document.location.href = "/admin/edit_products?" + filter_parameters;
});


function apply_filter_in_edit_products(){
    var form = $('#admin_product_multiselect_form');
    var filter_parameters = form.serialize();

    document.location.href = "/admin/edit_products?" + filter_parameters;
}


function save_product_review(){
    console.log('создание отзыва');

    var action = 'create';

    var text_review = document.getElementById("text_review_new").value;
    var rating = document.querySelector('.rating_star_container').dataset.rating;
    var product_id = document.querySelector('.rating_star_container').dataset.productId;

    if(!rating){
        rating = 1;
    }

    var review_information = {text_review: text_review, rating: rating, product_id:product_id};

    $.post("/product/review_crud/" + action , review_information, function (data) {
        document.location.href = "/product/"+product_id;
        },"html"
    );

}


function edit_product_review(event) {

    var edit_review_button = $('*[data-review-edit]')[0];
    var is_edited_now = edit_review_button.dataset.isEditedNow;

    if(is_edited_now === 'true'){
        return;
    } else {
        is_edited_now = 'true';
        edit_review_button.dataset.isEditedNow = is_edited_now;
        window.original_review_rating = $(".current_user_review .review_item .review_rating").eq(0).html();
        window.original_review_text = $(".current_user_review .review_item .review_text").eq(0).html();
    }

    console.log('изменение отзыва');

    var action = 'edit';

    var location = document.location.href;
    var segments = location.split('/');
    var product_id = segments[4];
    console.log('product_id =' + product_id);

    $.post("/product/review_crud/" + action, {product_id: product_id}, function (data) {

        // var result = JSON.parse(data);

        $(".current_user_review .review_item .review_rating").html(data.stars);
        $(".current_user_review .review_item .review_text").html(data.text);

        var container_location = document.querySelector('.current_user_review .review_item .review_rating');
        raiting_stars_inicialization(event, container_location);

        },"json"
    );

    document.querySelector('.review_update_and_cancel').hidden = false;

}

function delete_product_review() {
    console.log('удаление отзыва');

    var product_id = document.querySelector('.rating_star_container').dataset.productId;

    var action = 'delete';
    $.post("/product/review_crud/" + action , {product_id:product_id}, function (data) {
        document.location.href = "/product/"+product_id;
        },"html"
    );
}

function update_product_review() {
    console.log('обновление отзыва');

    var text_review = document.getElementById("text_review_update").value;
    var rating = document.querySelector('.rating_star_container').dataset.rating;
    var product_id = document.querySelector('.rating_star_container').dataset.productId;

    if(!rating){
        rating = 1;
    }

    var review_information = {text_review: text_review, rating: rating, product_id:product_id};

    var action = 'update';
    $.post("/product/review_crud/" + action, review_information, function (data) {
        document.location.href = "/product/"+product_id;
        },"html"
    );
}

function cancel_edit_review(){
    var edit_review_button = $('*[data-review-edit]')[0];
    var is_edited_now = edit_review_button.dataset.isEditedNow;

    $(".current_user_review .review_item .review_rating").eq(0).html(original_review_rating);
    $(".current_user_review .review_item .review_text").eq(0).html(original_review_text);
    is_edited_now = 'false';
    edit_review_button.dataset.isEditedNow = is_edited_now;
    document.querySelector('.review_update_and_cancel').hidden = true;
}



$(document).on('click', '.delete_product_review', function (event) {

    event.preventDefault();
    var review_id = $(this).attr("data-review_id");

    $.post("/admin/reviews_control/delete_review", {review_id: review_id}, function (data) {
        var location = document.location.href;
        document.location.href = location;
        },"html"
    );

});


$(document).on('click', '.show_review_rating_sorting', function(){

    var review_rating_sorting = document.querySelector('.review_rating_sorting');
    if(review_rating_sorting.hidden === false){
        review_rating_sorting.hidden = true;
    } else{
        review_rating_sorting.hidden = false;
    }
});

$(document).on('click', '.review_rating_sorting .sorting_ascending', function(){
    var location = document.location.href;
    location = location + '&sorting=ascending';
    document.location.href = location;
});

$(document).on('click', '.review_rating_sorting .sorting_descending', function(){
    var location = document.location.href;
    location = location + '&sorting=descending';
    document.location.href = location;
});

if($('[data-toggle="tooltip"]').length>0) {
    $('[data-toggle="tooltip"]').tooltip()
}


$(document).on('change', '.form-check-input', function(){
    console.log('работает');
    console.log(this.checked);

    var category_status;

    if(this.checked == true) {
        category_status = 1;
    } else {
        category_status = 0;
    }

    var category_status_cell = this.closest(".category_status_cell");
    var category_id = category_status_cell.dataset.categoryId;

    console.log('category_id = ' + category_id);

    information = {category_status:category_status, category_id: category_id};

    $.post("/admin/update_category_status", information, function (data) {
        },"html"
    );

});


$(document).on('click', '.add_new_product_main_container input[name ="availability_to_choose_the_color"]', function(){

    var value = $(this).val();

    if(value === '1'){
        $('.product_colors').css('display', 'block');
        $('.select_available_colors').css('display', 'block');
        $('.only_one_color_input').css('display', 'none');
    } else if(value === '0'){
        $('.product_colors').css('display', 'none');
        $('.select_available_colors').css('display', 'none');
        $('.only_one_color_input').css('display', 'block');
    }
});


$(document).on('click', '.edit_selected_product_main_container input[name ="availability_to_choose_the_color"]', function(){

    var value = $(this).val();

    if(value === '1'){
        $('[data-target="#AddNewColorModal"]').prop('disabled', false);
        $('.color_amount[data-color-id="1"]').remove();
    } else if(value === '0'){

        $('tr.color_amount').each(function( index ) {
            var table_row = $( this );
            var color_id = table_row.data("colorId");
            var color_name = table_row.data("colorName");
            $("#multiple_colors_list").append('<option value="' + color_id + '">' + color_name + '</option>');
            $('#multiple_colors_list').multiSelect('refresh');
            $(this).remove();
        });

        selected_colors = {1:'no_color'};
        $.post("/admin/edit_selected_product/load_new_rows_with_selected_colors", {selected_colors:selected_colors}, function (data) {
                $('.product_colors_table').append(data);
                // $('.delete_selected_product_color').addClass("disabled");
            },"html"
        );

        $('[data-target="#AddNewColorModal"]').prop('disabled', true);
    }
});

if($('#tabs').length>0) {
    $(function () {
        $("#tabs").tabs();
    });
}

if($('#multiple_colors_list').length>0) {

    $('.add_new_product_main_container #multiple_colors_list').multiSelect({
        selectableHeader: "<div class='custom-header'>Список цветов</div>",
        selectionHeader: "<div class='custom-header'>Выбранные цвета</div>",
        afterSelect: function (values) {

            var color_id = values;
            var all_colors_object = $('.all_colors_information').val();
            all_colors_object = JSON.parse(all_colors_object);
            var color_name = all_colors_object[color_id];

            $(".product_colors_table").append("<tr class='color_amount' data-color-id='" + color_id + "'><td>" + color_name + "</td> <td><input name='color[" + values + "]'></td> </tr>"
            );

        },
        afterDeselect: function (values) {
            $('.color_amount[data-color-id="' + values + '"]').remove();
        }
    });
}


if($('#AddNewColorModal #multiple_colors_list').length>0) {

    $('#AddNewColorModal #multiple_colors_list').multiSelect({
        selectableHeader: "<div class='custom-header'>Список цветов</div>",
        selectionHeader: "<div class='custom-header'>Выбранные цвета</div>",
        afterSelect: function (values) {
            $(".selected_colors").append("<input value='"+ values + "' disabled type='hidden' >");
        },
        afterDeselect: function (values) {
            $('.selected_colors input[value=' + values + ']').remove();
        }
    });
}

$(document).on('click', '.edit_selected_product_main_container .product_amount_decrement', function (e) {
    var input = $(this).parents('.amount_cell').find('.input_product_amount')[0];
    var product_amount = input.getAttribute('value');

    new_product_amount = +product_amount - 1;
    input.setAttribute('value', new_product_amount);

    if(new_product_amount === 0){
        $(this).attr("disabled", true);
    }
});

$(document).on('click', '.edit_selected_product_main_container .product_amount_increment', function (e) {
    var input = $(this).parents('.amount_cell').find('.input_product_amount')[0];
    var product_amount = input.getAttribute('value');

    new_product_amount = +product_amount + 1;
    input.setAttribute('value', new_product_amount);

    if(new_product_amount === 1){
        $(this).siblings('.product_amount_decrement').attr("disabled", false);
    }
});


$(document).on('click', '.add_colors_modal_submit', function (e) {

    var hidden_inputs = $('.selected_colors input');

    var selected_colors = {};

    var all_colors_object = $('.all_colors_information').val();
    all_colors_object = JSON.parse(all_colors_object);

    hidden_inputs.each(function( index ) {
        color_id = $(this).val();
        color_id = +color_id;
        color_name = all_colors_object[color_id];

        selected_colors[color_id] = color_name;
    });

    for (var key in selected_colors) {
        color_id = key;
        $("#multiple_colors_list option[value=" + color_id + "]").remove();
    }

    $('.selected_colors').empty();

    console.log('selected_colors');
    console.log(selected_colors);

    $.post("/admin/edit_selected_product/load_new_rows_with_selected_colors", {selected_colors:selected_colors}, function (data) {
        $('.product_colors_table').append(data);
        },"html"
    );

    $('#multiple_colors_list').multiSelect('refresh');

});

$(document).on('click', '.edit_selected_product_main_container .delete_selected_product_color', function (e) {

    var table_row = $(this).parents("tr.color_amount");
    var color_id = table_row.data("colorId");
    var color_name = table_row.data("colorName");

    if(color_name === 'no_color'){

        var input =  $(this).parents("tr.color_amount").find('.input_product_amount');

        console.log('input');
        console.log(input);

        input[0].setAttribute('value', 0);
        table_row.find('.product_amount_decrement').prop('disabled', true);

    } else {
        table_row.remove();
        $("#multiple_colors_list").append('<option value="' + color_id + '">' + color_name + '</option>');
        $('#multiple_colors_list').multiSelect('refresh');
    }
});
































// Не забыть включть!!!
// $.switcher('input[type=checkbox]');
// $.switcher('input[type=radio]');



















