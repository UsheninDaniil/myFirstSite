<html>
<head>

    <meta charset="utf-8">
    <link rel="stylesheet" href="/template/third_party_files/fontawesome-free-5.8.1-web/css/all.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



    <style>

        .rating_star_container {
            display: flex;
        }

        .fa-star.fas{
            color: transparent;
        }

        .fa-star.far{
            position: absolute;
        }

        .active_star > .fas{
            color: Gold !important;
        }



    </style>
</head>
<body>

<br/>

<div class="rating_star_container">

    <div data-rating="1" class="rating_star">
        <i class="fa-star far"></i>
        <i class="fa-star fas"></i>
    </div>

    <div data-rating="2" class="rating_star">
        <i class="fa-star far"></i>
        <i class="fa-star fas"></i>
    </div>

    <div data-rating="3" class="rating_star">
        <i class="fa-star far"></i>
        <i class="fa-star fas"></i>
    </div>

    <div data-rating="4" class="rating_star">
        <i class="fa-star far"></i>
        <i class="fa-star fas"></i>
    </div>

    <div data-rating="5" class="rating_star">
        <i class="fa-star far"></i>
        <i class="fa-star fas"></i>
    </div>

</div>


<script>

    var rating_container = document.querySelector('.rating_star_container'),
        ratingItem = document.querySelectorAll('.rating_star');


    rating_container.onclick = function(e){
        var target = e.target;

        console.log("click_target");
        console.log(target);

        if(target.parentElement.classList.contains('rating_star')){
            removeClass(ratingItem,'current-active');
            target.parentElement.classList.add('active_star', 'current-active');

            rating_container.dataset.rating = target.parentElement.dataset.rating;
        }
    };

    rating_container.onmouseover = function(e) {
        var target = e.target;

        console.log("mouseover_target");
        console.log(target);

        if(target.parentElement.classList.contains('rating_star')){
            removeClass(ratingItem,'active_star');

            target.parentElement.classList.add('active_star');
            mouseOverActiveClass(ratingItem);
        }
    };

    rating_container.onmouseout = function(){
        addClass(ratingItem,'active_star');
        mouseOutActiveClas(ratingItem);
    };

    function removeClass(arr) {
        for(var i = 0, iLen = arr.length; i <iLen; i ++) {
            for(var j = 1; j < arguments.length; j ++) {
                ratingItem[i].classList.remove(arguments[j]);
            }
        }
    }
    function addClass(arr) {
        for(var i = 0, iLen = arr.length; i <iLen; i ++) {
            for(var j = 1; j < arguments.length; j ++) {
                ratingItem[i].classList.add(arguments[j]);
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

</script>



</body>
</html>