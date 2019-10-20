document.getElementById('next').onclick = Next;
document.getElementById('previous').onclick = Previous;

selected_photo_container = document.getElementById('selected_photo_container');
var selected_photo_container_width = $(selected_photo_container).width();
selected_photo_container_width = Math.floor(selected_photo_container_width);
console.log('selected_photo_container_width=' + selected_photo_container_width);
selected_photo_container.dataset.width = selected_photo_container_width;

function Next(){

    var polosa = document.getElementById('polosa');

    var slider = document.getElementById("slider");
    photo_amount = slider.dataset.photoAmount;
    photo_on_page = slider.dataset.photoOnPage;
    element_height = slider.dataset.elementHeight;
    current_top_value = slider.dataset.currentTopValue;

    top_max = -(photo_amount - photo_on_page)*element_height;

    // alert("top до нажатия = " + current_top_value);

    if(current_top_value > top_max){
        current_top_value = (+current_top_value) - (+element_height);
    }

    // alert("top после нажатия = " + current_top_value);

    slider.dataset.currentTopValue = current_top_value;

    polosa.style.top = current_top_value +'px';
}

function Previous() {

    var polosa = document.getElementById('polosa');

    var slider = document.getElementById("slider");
    photo_amount = slider.dataset.photoAmount;
    photo_on_page = slider.dataset.photoOnPage;
    element_height = slider.dataset.elementHeight;
    current_top_value = slider.dataset.currentTopValue;

    top_max = -(photo_amount - photo_on_page)*element_height;

    // alert("top до нажатия = " + current_top_value);

    if(current_top_value != 0){
        current_top_value = (+current_top_value) + (+element_height);
    }

    // alert("top после нажатия = " + current_top_value);

    slider.dataset.currentTopValue = current_top_value;

    polosa.style.top = current_top_value +'px';
}

function AddBorder() {
    $.post("/admin/remove_parameter_from_category/"+remove_parameter_id+"/"+category_id, {}, function (data) {
            $("#selected_photo_container").html(data);
        }, "html"
    );
}


function ShowSelectedPhoto(element_div)
{
    small_photo = element_div.getElementsByTagName('img')[0];
    border_width = small_photo.dataset.borderWidth;

    $(".element").css("border-color", "transparent");
    element_div.style.border = border_width +"px solid mediumseagreen";

    current_source = small_photo.src;
    document.getElementById ('selected_photo').src = current_source.replace('small_images', 'middle_images');

    // setTimeout(ResizeSelectedPhoto(), 2000);

}

function ResizeSelectedPhoto(){
    selected_photo = document.getElementById('selected_photo');
    var selected_photo_width = $(selected_photo).width();

    console.log('до');
    console.log('selected_photo_width =' + selected_photo_width);
    console.log('selected_photo_container_width =' + selected_photo_container_width);

    if((+selected_photo_width) > (+selected_photo_container_width)) {
        selected_photo_width = +selected_photo_container_width;
        console.log('условие выполняется, размер фотографии уменьшен');
        selected_photo.style.width = selected_photo_width + 'px';
    }

    console.log('после');
    console.log('selected_photo_width =' + selected_photo_width);
    console.log('selected_photo_container_width =' + selected_photo_container_width);
    //
}


var elem = document.getElementById('slider');

if (elem.addEventListener) {
    if ('onwheel' in document) {
        // IE9+, FF17+
        elem.addEventListener("wheel", onWheel);
    } else if ('onmousewheel' in document) {
        // устаревший вариант события
        elem.addEventListener("mousewheel", onWheel);
    } else {
        // Firefox < 17
        elem.addEventListener("MozMousePixelScroll", onWheel);
    }
} else { // IE8-
    elem.attachEvent("onmousewheel", onWheel);
}

// Это решение предусматривает поддержку IE8-
function onWheel(e) {
    e = e || window.event;
    if (e.deltaY > 0) {
        Next();
    } else if(e.deltaY < 0){
        Previous();
    }
    e.preventDefault ? e.preventDefault() : (e.returnValue = false);
}

