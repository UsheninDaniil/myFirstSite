function raiting_stars_inicialization(){

    console.clear();

    var rating_container = document.getElementsByClassName('rating_star_container');
    console.log('rating_container');
    console.log(rating_container);

    var ratingItem = [];
    var current_container_number = undefined;

    for (var i = 0; i < rating_container.length; i++) {
        current_container_number = i;
        console.log('элемент' + i);
        console.log(rating_container[i]);
        ratingItem[i] = rating_container[i].querySelectorAll('.rating_star');

        console.log('Длина');
        console.log(ratingItem[i].length);

        console.log('список звездочек');
        console.log(ratingItem[i]);

        rating_container[i].onclick = function(e){

            var target = e.target;

            if(target.parentElement.classList.contains('rating_star')){
                removeClass(ratingItem[current_container_number],'current-active');
                target.parentElement.classList.add('active_star', 'current-active');

                rating_container[current_container_number].dataset.rating = target.parentElement.dataset.rating;
            }
        };

        rating_container[i].onmouseover = function(e) {

            console.log('вызвана функция OnMouseOver');
            console.log('current_container_number');
            console.log(current_container_number);


            var target = e.target;

            if(target.parentElement.classList.contains('rating_star')){
                removeClass(ratingItem[current_container_number],'active_star');

                target.parentElement.classList.add('active_star');
                mouseOverActiveClass(ratingItem[current_container_number]);
            }
        };

        rating_container[i].onmouseout = function(e){
            var target = e.target;

            addClass(ratingItem[current_container_number],'active_star');
            mouseOutActiveClas(ratingItem[current_container_number]);
        };


        function removeClass(arr) {

            for(var i = 0, iLen = arr.length; i <iLen; i ++) {
                for(var j = 1; j < arguments.length; j ++) {
                    arr[i].classList.remove(arguments[j]);
                }
            }
        }
        function addClass(arr) {
            for(var i = 0, iLen = arr.length; i <iLen; i ++) {
                for(var j = 1; j < arguments.length; j ++) {
                    arr[i].classList.add(arguments[j]);
                }
            }
        }

        function mouseOverActiveClass(arr){
            for(var i = 0, iLen = arr.length; i < iLen; i++) {
                if(arr[i].classList.contains('active_star')){
                    break;
                }else {
                    arr[i].classList.add('active_star');
                }
            }
        }

        function mouseOutActiveClas(arr){
            for(var i = arr.length-1; i >=0; i--) {
                if(arr[i].classList.contains('current-active')){
                    break;
                }else {
                    arr[i].classList.remove('active_star');
                }
            }
        }

    }

}


document.addEventListener("DOMContentLoaded", raiting_stars_inicialization);
