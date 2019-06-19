var inputElement = document.getElementById("input");
inputElement.addEventListener("change", handleFiles, false);

var photo_number = 0;

photo_name_list = "";

function handleFiles(files) {

    for (var i = 0; i < files.length; i++) {

        var file = files[i];

        var size = file.size;
        size = +size/1048576;
        size = Math.round(size * 100) / 100;

        photo_number = +photo_number + 1;

        if (!file.type.startsWith('image/')){ continue }

        var img = document.createElement("img");
        img.dataset.photoNumber = i+1;

        naturalWidth = null;
        naturalHeight = null;

        var element = document.createElement("div");
        element.className = "element draggable droppable";

        test = document.getElementById("test");

        document.getElementById("images_container").insertBefore(element,test);

        img.onload = function(){
            photo_number = this.dataset.photoNumber;
            // console.log("Фото " + photo_number);
            // console.log(this);
            naturalWidth = this.naturalWidth;
            // console.log("naturalWidth = " + naturalWidth);
            naturalHeight = this.naturalHeight;
            // console.log("naturalHeight = " + naturalHeight);
            photo_width = document.getElementsByClassName('photo_width')[photo_number-1];
            photo_height = document.getElementsByClassName('photo_height')[photo_number-1];
            photo_width.innerHTML = naturalWidth;
            photo_height.innerHTML = naturalHeight;
        };

        element.innerHTML = "<div class='photo_management'></div><div class='photo_container'></div><div class='photo_information'></div>";

        photo_information = element.getElementsByClassName('photo_information')[0];
        photo_information.innerHTML = "фото #" + photo_number +
            "<br/>name = " + file.name +
            "<br/>size = " + size + " MB " +
            "<br/><span class='photo_width'>width</span>" + " x " + "<span class='photo_height'>height</span>";

        photo_management = element.getElementsByClassName('photo_management')[0];
        photo_management.innerHTML = "<a href='javascript:void(0);' class='delete_selected_photo' data-id='"+photo_number+"'><i class='far fa-times-circle'></i></a>";

        photo_container = element.getElementsByClassName('photo_container')[0];
        photo_container.appendChild(img);

        element.dataset.id = photo_number;
        element.dataset.photoName = file.name;


        var reader = new FileReader();

        function load_image(current_image) {
            return function(e) {
                current_image.src = e.target.result;
            };
        };

        reader.onload = load_image(img);

        reader.readAsDataURL(file);

        img.style.maxWidth  = "100px";
        img.style.maxHeight  = "100px";

        photo_name = file.name;

        if (photo_name_list === "") {
            photo_name_list = photo_name_list + photo_name;
        } else {
            photo_name_list = photo_name_list + ", " + photo_name;
        }

        var image_names = document.getElementById("image_names");
        image_names.value = photo_name_list;

        // console.log("photo_number_list = " + photo_name_list);

    }
}

$(document).on('click', '.delete_selected_photo', function (event)
{
    photo_number = this.dataset.id;

    console.log("удаление фотографии № " + photo_number);

    photo_name = "";

    $( ".element" ).each(function() {
        if(this.dataset.id == photo_number){
            element = this;
            photo_name = element.dataset.photoName;
        }
    });

    var image_names = document.getElementById("image_names");
    photo_name_list = image_names.value;
    photo_name_list = photo_name_list.replace('/^' + photo_name,'');
    photo_name_list = photo_name_list.replace(', ' + photo_name,'');
    image_names.value = photo_name_list;

    var images_container = document.getElementById("images_container");

    console.log(element);

    images_container.removeChild(element);

});
