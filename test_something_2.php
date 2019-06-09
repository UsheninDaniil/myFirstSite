<html>
<head>
    <link rel="stylesheet" href="/template/fontawesome-free-5.8.1-web/css/all.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

        .element {
            width:120px;
            height:220px;
            border: 1px solid black;
            display: flex;
            flex-direction: column;
            position: relative;
            margin-right: 5px;
        }

        .photo_container{
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: SteelBlue;
            width: 100%;
            flex-grow: 1;
        }

        .photo_information{
            background-color: Thistle	;
            width: 100%;
        }

        .photo_management{
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            padding: 5px 5px 0 5px;
            background-color: SteelBlue;
        }

        .photo_management a{
            color: black;
        }

        .photo_management a:hover{
            color: red;
        }

        #images_container{
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        b{
            color: darkred;
        }

        .photo_width, .photo_height{
            color: darkred;
        }

    </style>
</head>
<body>

<form  enctype="multipart/form-data" action="" method ="post">
    <input type="hidden" name="image_names" value="" id="image_names">
    <input type="file" name="images[]" id="input" multiple onchange="handleFiles(this.files)">
    <button type="submit" name="save" value="Сохранить">Сохранить</button>
</form>

<div id="images_container">
    <div id="test"></div>
</div>

<?php


if(!empty($_POST)){
    $image_names_to_upload = $_POST['image_names']; //в виде строки
    $image_names_to_upload = explode(", ", $image_names_to_upload);
    echo "содержимое <b>image_names_to_upload</b> <br />";
    print_r($image_names_to_upload);
}

if(!empty($_FILES)){

    $tmp_name_array = $_FILES["images"]["tmp_name"];
    $amount_of_files = count($tmp_name_array);
    echo "<br /><br /><b>total_amount_of_files</b> = $amount_of_files <br/>";
    echo "<br/> Файлы которые будут отправлены на сервер <br/>";

    for ($i = 0; $i <= ($amount_of_files-1); $i++) {
        $tmp_name = $_FILES["images"]["tmp_name"][$i];
        $name = $_FILES["images"]["name"][$i];

        if($name = $image_names_to_upload[$i]){
            echo "<br/><b>Файл # $i</b><br />";
            echo "name = $name <br/>";
            echo "tmp_name = $tmp_name <br/>";
        }
    }




}


?>

<script src="/template/jquery-3.3.1.min.js"></script>

<script>
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
            element.className = "element";

            test = document.getElementById("test");

            document.getElementById("images_container").insertBefore(element,test);

            img.onload = function(){
                photo_number = this.dataset.photoNumber;
                console.log("Фото " + photo_number);
                console.log(this);
                naturalWidth = this.naturalWidth;
                console.log("naturalWidth = " + naturalWidth);
                naturalHeight = this.naturalHeight;
                console.log("naturalHeight = " + naturalHeight);
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

            console.log("photo_number_list = " + photo_name_list);

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

</script>
</body>
</html>