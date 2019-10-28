<?php
/**
 * Created by PhpStorm.
 * User: Даня
 * Date: 24.10.2019
 * Time: 20:59
 */

class Images
{
    public static function save_product_photo_to_database($product_id, $photo_number, $photo_name)
    {
        $mysqli = DatabaseConnect::connect_to_database();

        $mysqli->query("INSERT INTO product_photos (product_id, photo_number, photo_name) VALUES ('$product_id', '$photo_number', '$photo_name')");

        DatabaseConnect::disconnect_database($mysqli);
    }

    public static function resize_and_save_product_photo($max_height, $max_width, $filename, $height_orig, $width_orig, $ratio_orig, $new_path)
    {
        if ($height_orig > $max_height) {
            $height = $max_height;
            $width = $height * $ratio_orig;
        } else {
            $height = $height_orig;
            $width = $height * $ratio_orig;
        }
        // если получившаяся ширина больше максимальной - пропорционально уменьшить фотографию до допустимой ширины
        if ($width > $max_width) {
            $width = $max_width;
            $height = $width / $ratio_orig;
        }
        // ресэмплирование
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // сохранение
        imagejpeg($image_p, $new_path, 100);
    }

    public static function save_product_photos_for_a_new_product($product_id, $files, $post)
    {
        $photo_number = 1;
        $tmp_name_array_after_sorting = [];

        //получаем строку состорящую из фотографий, в том порядке, в котором они были отсортированы с помощью drag-and-drop
        $sorted_image_names = $post['image_names'];
        $sorted_image_names = explode(", ", $sorted_image_names);

        $i = 0;

        // проверяем каждую фотографию, была ли она загружена, и формируем новый массив, состоящий из отсортированных "tmp_name"
        foreach ($sorted_image_names as $image_name) {
            if (in_array($sorted_image_names, $files["images"]["name"])) {
                $key = array_search($sorted_image_names, $files["images"]["name"]);
                $name = $files["images"]["name"][$key];
                $tmp_name = $files["images"]["tmp_name"][$key];
                array_push($tmp_name_array_after_sorting, $tmp_name);
                $i = $i + 1;
            }
        }

        foreach ($tmp_name_array_after_sorting as $tmp_name) {
            Images::save_new_product_photo_in_different_sizes($product_id, $photo_number, $tmp_name);
            $photo_number = $photo_number + 1;
        }

    }

    public static function save_new_product_photo_in_different_sizes($product_id, $photo_number, $tmp_name)
    {
        if (is_uploaded_file($tmp_name)) {
            // Если загружалось, переместим его в нужную папке, дадим новое имя $photo
            move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'] . "/images/large_images/id_{$product_id}_photo_{$photo_number}.jpg");

            $photo_name = "id_{$product_id}_photo_{$photo_number}.jpg";

            Images::save_product_photo_to_database($product_id, $photo_number, $photo_name);

            $filename = $_SERVER['DOCUMENT_ROOT'] . "/images/large_images/id_{$product_id}_photo_{$photo_number}.jpg";

            // получение новых размеров
            list($width_orig, $height_orig) = getimagesize($filename);

            $ratio_orig = $width_orig / $height_orig;

            // preview_image - отображается на главной странице и странице категории
            $images_folder = "preview_images";
            $new_path = $_SERVER['DOCUMENT_ROOT'] . "/images/{$images_folder}/id_{$product_id}_photo_{$photo_number}.jpg";
            Images::resize_and_save_product_photo(200, 300, $filename, $height_orig, $width_orig, $ratio_orig, $new_path);

            // middle_image - отображается на странице товара
            $images_folder = "middle_images";
            Images::resize_and_save_product_photo(350, 525, $filename, $height_orig, $width_orig, $ratio_orig, $new_path);

            // small_image - отображается в слайдере на странице товара
            $images_folder = "small_images";
            Images::resize_and_save_product_photo(50, 75, $filename, $height_orig, $width_orig, $ratio_orig, $new_path);
        }
    }

}