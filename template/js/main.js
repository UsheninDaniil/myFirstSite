<!--Управление корзиной-->

$(document).ready(function () {
    $(".add_to_cart_from_modal").click(function (event) {

        // var product_id = $(this).data("productId");
        // var color_id = $(this).data("colorId");
        // var product_amount = $(this).data("productAmount");

        var product_id = $(this).attr("data-product-id");
        var color_id = $(this).attr("data-color-id");
        var product_amount = $(this).attr("data-product-amount");

        console.log('%cтовар добавлен в корзину ', 'color: darkred');
        console.log('product_id = ' + product_id);
        console.log('color_id = ' + color_id);
        console.log('product_amount = ' + product_amount);

        $.post("/product/add/", {product_id: product_id, color_id: color_id, product_amount:product_amount}, function (data) {
                $(".cart-count").html(data);
                $(".check-in-the-cart" + product_id).html("<i class='far fa-check-square'></i>");
            }, "html"
        );
    });


    $(".add_to_cart").click(function (event) {

        if($(this).is("[data-color-id]")) {
            var product_id = $(this).attr("data-product-id");
            var color_id = $(this).attr("data-color-id");
            var product_amount = 1;

            $.post("/product/add/", {
                    product_id: product_id,
                    color_id: color_id,
                    product_amount: product_amount
                }, function (data) {
                    $(".cart-count").html(data);
                    $(".check-in-the-cart" + product_id).html("<i class='far fa-check-square'></i>");
                }, "html"
            );
        } else {
            $('.select_color_warning').css('display', 'block');
        }
    });




    $(".add-to-compare").click(function (event) {

        var product_id = $(this).data("id");
        $.post("/product/add_compare/", {product_id: product_id}, function (data) {
                $(".compare-count").html(data);
                $(".check-in-the-compare" + product_id).html("<i class='far fa-check-square'></i>");
            }, "html"
        );
    });

    $(document).on('click', '.color_link', function (e) {

        if($(this).parents('.view_product_information_block').length > 0){

            $('.select_color_warning').css('display', 'none');
            var color_id = $(this).data("colorId");

            var add_to_cart = $('.add_to_cart');
            add_to_cart.eq(0).attr('data-color-id', color_id);

            var color_squares = $('.color_check');

            if (color_squares.length > 0) {
                color_squares.each(function (index) {
                    color_squares[index].style.color = 'transparent';
                });
            }

            var current_color_square = $(this).find('.color_check');
            $(this).find('.color_check').css('color', 'white');


        } else if($(this).parents('#addToCartModal').length > 0) {

            $('.select_color_warning').css('display', 'none');
            $('.product_amount_increment').removeAttr("disabled");
            $('.product_amount_decrement').attr('disabled', 'disabled');

            var color_id = $(this).data("colorId");

            var add_to_cart_from_modal = $('.add_to_cart_from_modal');
            add_to_cart_from_modal.eq(0).attr('data-color-id', color_id);
            add_to_cart_from_modal.eq(0).attr('data-product-amount', 1);

            var color_squares = $('.color_check');

            if (color_squares.length > 0) {
                color_squares.each(function (index) {
                    color_squares[index].style.color = 'transparent';
                });
            }

            var current_color_square = $(this).find('.color_check');
            $(this).find('.color_check').css('color', 'white');

            var input = document.querySelector('.input_product_amount');
            input.dataset.currentColorId = color_id;

            $('.input_product_amount').attr('value', 1);
        }

    });

    $('#addToCartModal').on('show.bs.modal', function (event) {

        var modal = $(this);
        var button = $(event.relatedTarget);
        var product_id = button.data('productId');
        var ability_to_choose_the_color = button.attr('data-ability-to-choose-the-color');

        $.post("/product/load_modal_to_select_product_color", {product_id: product_id, ability_to_choose_the_color:ability_to_choose_the_color}, function (data) {
            modal.find('.modal-body').html(data);

            // По умолчанию будет выбран первый цвет в списке
            if(ability_to_choose_the_color === 'true') {
                var first_color_link = $('.color_link').eq(0);
                var color_id = first_color_link.data("colorId");
                first_color_link.find('.color_check').css('color', 'white');
            } else {
                color_id = 1;
            }

            modal.find('.input_product_amount').attr('data-current-color-id', color_id);
            modal.find('.add_to_cart_from_modal').attr('data-color-id', color_id);
            modal.find('.add_to_cart_from_modal').attr('data-product-amount', 1);
            modal.find('.add_to_cart_from_modal').attr('data-product-id', product_id);
            }, "html"
        );

        modal.find('.modal-title').text('Выберите колличество');
    });

    $('#please_log_in_to_vote_review').on('show.bs.modal', function (event) {


    });


    $(document).on('click', '.modal_input_product_amount .product_amount_decrement', function (e) {
        var input = document.querySelector('.input_product_amount');
        var product_amount = input.getAttribute('value');
        var color_id = input.dataset.currentColorId;

        if (color_id === undefined) {
            console.log('Выберите цвет товара');
            $('.select_color_warning').css('display', 'block');
            return;
        }

        var attribute_name = "[data-color-id='" + color_id + "']";
        var hidden_input = document.querySelector('input' + attribute_name);
        new_product_amount = +product_amount - 1;
        max_product_amount = hidden_input.value;

        input.setAttribute('value', new_product_amount);
        $('.add_to_cart_from_modal').attr('data-product-amount', new_product_amount);

        if (new_product_amount == 1) {
            $(this).attr('disabled', 'disabled');
        }

        if (max_product_amount > new_product_amount) {
            $('.product_amount_increment').removeAttr("disabled")
        }
    });

    $(document).on('click', '.modal_input_product_amount .product_amount_increment', function (e) {
        var input = document.querySelector('.input_product_amount');
        var product_amount = input.getAttribute('value');
        var color_id = input.dataset.currentColorId;

        if (color_id === undefined) {
            console.log('Выберите цвет товара');
            $('.select_color_warning').css('display', 'block');
            return;
        }

        var attribute_name = "[data-color-id='" + color_id + "']";
        var hidden_input = document.querySelector('input' + attribute_name);
        new_product_amount = +product_amount + 1;
        max_product_amount = hidden_input.value;

        input.setAttribute('value', new_product_amount);
        $('.add_to_cart_from_modal').attr('data-product-amount', new_product_amount);

        if (new_product_amount == max_product_amount) {
            $(this).attr('disabled', 'disabled');
        }

        if (new_product_amount > 1) {
            $('.product_amount_decrement').removeAttr("disabled")
        }
    });


    $(document).on('click', '.review_vote_like.user_is_logged_in', function (e) {

        console.log('Нажат лайк');

        var user_vote_before_click =  $(this).siblings(".user_vote").val();
        var like_amount = $(this).siblings(".like_amount").html();
        var dislike_amount = $(this).siblings(".dislike_amount").html();
        var review_id = $(this).data('reviewId');
        var vote = true;

        console.log('user_vote_before_click = ' + user_vote_before_click);

        // Если у этого отзыва уже есть лайк от пользователя - он убирается
        if(user_vote_before_click === '1'){
            console.log('лайк ветка 1');
            like_amount = +like_amount -1;
            $.post("/product/delete_review_vote", {review_id: review_id, vote:vote}, function (data) {

                }, "html"
            );
            $(this).css("color", "black");
            $(this).siblings(".review_vote_dislike").css("color", "black");
            $(this).siblings(".dislike_amount").html(dislike_amount);
            $(this).siblings(".like_amount").html(like_amount);
            $(this).siblings(".user_vote").val('not_exist');
            return;
        }

        // Если у этого отзыва стоял дизлайк пользователя - коллличество лайков увеличивается, а дизлайков - уменьшается
        if(user_vote_before_click === '0'){
            console.log('лайк ветка 2');
            like_amount = +like_amount + 1;
            dislike_amount = +dislike_amount - 1;
            $(this).siblings(".user_vote").val('1');
        }

        // Если у этого отзыва не было лайка/дизлайка от пользовпателя - колличество лайков увеличивается на 1
        if(user_vote_before_click === 'not_exist'){
            console.log('лайк ветка 3');
            like_amount = +like_amount + 1;
            $(this).siblings(".user_vote").val('1');
        }

        console.log('новое колличество лайков ' + like_amount);
        console.log('новое колличество дизлайков ' + dislike_amount);

        $.post("/product/save_review_vote", {review_id: review_id, vote:vote}, function (data) {

            }, "html"
        );

        $(this).css("color", "green");
        $(this).siblings(".review_vote_dislike").css("color", "black");

        $(this).siblings(".dislike_amount").html(dislike_amount);
        $(this).siblings(".like_amount").html(like_amount);
    });

    $(document).on('click', '.review_vote_dislike.user_is_logged_in', function (e) {

        console.log('Нажат дизлайк');

        var user_vote_before_click =  $(this).siblings(".user_vote").val();
        var like_amount = $(this).siblings(".like_amount").html();
        var dislike_amount = $(this).siblings(".dislike_amount").html();
        var review_id = $(this).data('reviewId');
        var vote = false;

        console.log('user_vote_before_click = ' + user_vote_before_click);

        // Если у этого отзыва уже есть дизлайк от пользователя - он убирается
        if(user_vote_before_click === '0'){
            console.log('дизлайк ветка 1');
            dislike_amount = +dislike_amount -1;
            $.post("/product/delete_review_vote", {review_id: review_id, vote:vote}, function (data) {

                }, "html"
            );
            $(this).css("color", "black");
            $(this).siblings(".review_vote_like").css("color", "black");
            $(this).siblings(".dislike_amount").html(dislike_amount);
            $(this).siblings(".like_amount").html(like_amount);
            $(this).siblings(".user_vote").val('not_exist');
            return;
        }

        // Если у этого отзыва стоял лайк пользователя - коллличество дизлайков увеличивается, а лайков - уменьшается
        if(user_vote_before_click === '1'){
            console.log('дизлайк ветка 2');
            dislike_amount = +dislike_amount + 1;
            like_amount = +like_amount - 1;
            $(this).siblings(".user_vote").val('0');
        }

        // Если у этого отзыва не было лайка/дизлайка от пользовпателя - колличество дизлайков увеличивается на 1
        if(user_vote_before_click === 'not_exist'){
            console.log('дизлайк ветка 3');
            dislike_amount = +dislike_amount + 1;
            $(this).siblings(".user_vote").val('0');
        }

        console.log('новое колличество лайков ' + like_amount);
        console.log('новое колличество дизлайков ' + dislike_amount);

        $.post("/product/save_review_vote", {review_id: review_id, vote:vote}, function (data) {

            }, "html"
        );

        $(this).css("color", "red");
        $(this).siblings(".review_vote_like").css("color", "black");

        $(this).siblings(".dislike_amount").html(dislike_amount);
        $(this).siblings(".like_amount").html(like_amount);

    });


    $(document).on('click', '.leave_a_review_comment', function (e){

        console.log('вы нажали оставить комментарий');

        var display_status = $('.new_review_comment_container').css( "display" );
        var new_display_status = undefined;

        if(display_status === 'block'){
            new_display_status = 'none';
        } else {
            new_display_status = 'block';
        }

        $('.new_review_comment_container').css("display", new_display_status);

    });

    $(document).on('click', '.save_new_review_comment.user_is_logged_in', function (e){

        var form = $(this).parents('.review_item').find('.new_review_comment_form');
        var text = form.find('textarea[name="text"]').val();
        var review_id = form.find('textarea[name="text"]').data('reviewId');
        var review_comments_container = $(this).parents('.review_item').find('.review_comments_container');

        var comments_container_display_status = review_comments_container.css('display');

        var show_more_review_comments_container = $(this).parents('.review_item').find('.show_more_review_comments_container');

        $.post("/product/save_new_review_comment", {text:text, review_id:review_id}, function (data) {
                // если комментарии к этому отзыву открыты - добавить отзыв в конец,
                // а если нет - вывести отзыв и кнопку "показать еще 5 из n отзывов"
                if(comments_container_display_status === 'block'){
                    review_comments_container.append(data);
                } else {
                    show_more_review_comments_container.css('display', 'block');
                    review_comments_container.css('display', 'block');
                    review_comments_container.html(data);
                }
            }, "html"
        );

        var already_loaded = 1;
        var amount_to_load = show_more_review_comments_container.data('amountToLoad');
        var comments_amount = show_more_review_comments_container.data('commentsAmount');
        var how_many_to_show = undefined;
        var how_many_left = undefined;

        if(amount_to_load>(comments_amount-already_loaded)){
            how_many_to_show = comments_amount-already_loaded;
        } else{
            how_many_to_show = amount_to_load;
        }

        comments_amount = comments_amount +1;

        how_many_left = comments_amount-already_loaded;

        review_comments_container.attr('data-comments-amount', comments_amount);
        show_more_review_comments_container.attr('data-comments-amount', comments_amount);
        show_more_review_comments_container.attr('data-already-loaded', already_loaded);
        show_more_review_comments_container.find('.how_many_to_show').html(how_many_to_show);
        show_more_review_comments_container.find('.how_many_left').html(how_many_left);

        $(this).parents('.review_item').find('.new_review_comment_container').css("display", 'none');
        $(this).parents('.review_item').find('.review_comments_amount').html(+comments_amount);

    });


    $(document).on('click', '.show_review_comments', function (e){

        var review_comments_container = $(this).parents('.review_item').find('.review_comments_container');
        var display_status = review_comments_container.css('display');
        var new_display_status = undefined;
        var comments_amount = review_comments_container.data('commentsAmount');
        var show_more_review_comments_container = $(this).parents('.review_item').find('.show_more_review_comments_container');
        var already_loaded = +show_more_review_comments_container.attr('data-already-loaded');

        if(display_status === 'block'){
            new_display_status = 'none';
        } else {
            new_display_status = 'block';
        }

        review_comments_container.css("display", new_display_status);

        if((comments_amount > 5)&&((comments_amount - already_loaded)>0)){
            show_more_review_comments_container.css("display", new_display_status);
        }
    });


    $(document).on('click', '.show_more_review_comments', function (e){

        var show_more_review_comments_container = $(this).parents('.show_more_review_comments_container');

        var already_loaded = +show_more_review_comments_container.attr('data-already-loaded');
        var amount_to_load = show_more_review_comments_container.data('amountToLoad');
        var comments_amount = show_more_review_comments_container.attr('data-comments-amount');
        var review_id = show_more_review_comments_container.data('reviewId');

        var review_comments_information = {
            review_id:review_id,
            already_loaded:already_loaded,
            amount_to_load:amount_to_load,
            comments_amount:comments_amount,
        };

        $.post("/product/show_more_review_comments", {review_comments_information}, function (data) {
                $(".review_comments_container").prepend(data);
            }, "html"
        );

        if((comments_amount-already_loaded) < amount_to_load){
            already_loaded = already_loaded + (comments_amount-already_loaded)
        } else {
            already_loaded = already_loaded + amount_to_load;
        }

        // show_more_review_comments_container.data("alredyLoaded", already_loaded);

        show_more_review_comments_container.attr('data-already-loaded', already_loaded);

        console.log('новый data-already-loaded');
        console.log(show_more_review_comments_container.data('alreadyLoaded'));

        var how_many_to_show = undefined;
        var how_many_left = undefined;

        if(amount_to_load>(comments_amount-already_loaded)){
            how_many_to_show = comments_amount-already_loaded;
        } else{
            how_many_to_show = amount_to_load;
        }

        how_many_left = comments_amount-already_loaded;

        console.log('how_many_left');
        console.log(how_many_left);
        console.log('comments_amount');
        console.log(comments_amount);
        console.log('already_loaded');
        console.log(already_loaded);

        $(this).find('.how_many_to_show').html(how_many_to_show);
        $(this).find('.how_many_left').html(how_many_left);

        if(already_loaded >= comments_amount){
            show_more_review_comments_container.css("display", 'none');
        }

    });


    $(document).on('click', '.category_filter_tags tag x', function (e) {

        var tag_name = $(this).next().text();
        tag_name = $.trim(tag_name);

        var location = window.location.href;
        var get_parameters = location.split('?')[1];
        var location_without_get_parameters = location.split('?')[0];

        console.log('get_parameters');
        console.log(get_parameters);

        $.post("/category/delete_filter_tag", {tag_name: tag_name, get_parameters:get_parameters}, function (data) {
                var new_get_parameters = data;
                console.log('new_get_parameters');
                console.log(new_get_parameters);
                document.location.href = '/category/2' + '?' + new_get_parameters;

            }, "html"
        );

    });

    $(document).on('click', '.delete_product_from_compare_list', function (e) {

        var product_id = $(this).attr('data-product-id');
        var category_id = $(this).attr('data-category-id');

        $.post("/product/delete_product_from_compare_list", {product_id:product_id, category_id:category_id}, function (data) {
                document.location.href = '/compare_category/' + category_id;
            }, "html"
        );
    });



});