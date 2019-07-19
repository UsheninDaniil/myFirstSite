var ratingItem = [];
var rating_container = undefined;


function raiting_stars_inicialization(){

    console.clear();

    rating_container = document.getElementsByClassName('rating_star_container');
    console.log('rating_container');
    console.log(rating_container);

    ratingItem = [];

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

        rating_container[current_container_number].addEventListener("mouseover", function(){
            rating_container_mouseover(event, current_container_number);
        });

        rating_container[current_container_number].addEventListener("mouseout", function(){
            rating_container_mouseout(event, current_container_number);
        });

        rating_container[current_container_number].addEventListener("click", function(){
            rating_container_click(event, current_container_number);
        });
    }
}


function rating_container_click(event, current_container_number){

    console.log('ок');

    var target = event.target;

    if(target.parentElement.classList.contains('rating_star')){
        removeClass(ratingItem[current_container_number],'current-active');
        target.parentElement.classList.add('active_star', 'current-active');

        rating_container[current_container_number].dataset.rating = target.parentElement.dataset.rating;
    }
};

function rating_container_mouseover(event, current_container_number) {

    var target = event.target;

    console.clear();
    console.log('%cВызвана функция OnMouseOver ', 'color: red');

    console.log('%ccurrent_container_number ', 'color: green');
    console.log(current_container_number);

    console.log('%cTARGET ', 'color: green');
    console.log(target);

    if(target.parentElement.classList.contains('rating_star')){
        removeClass(ratingItem[current_container_number],'active_star');

        target.parentElement.classList.add('active_star');
        mouseOverActiveClass(ratingItem[current_container_number]);
    }
};

function rating_container_mouseout(e, current_container_number){
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





document.addEventListener("DOMContentLoaded", raiting_stars_inicialization);
















