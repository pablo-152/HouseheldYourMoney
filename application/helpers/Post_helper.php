<?php

function estadoslide() {
    return array("0" => "Seleccionar","1" => "Activado", "2" => "Desactivado");
}

function tiposlide() {
    return array("0" => "Seleccionar","1" => "Imagen", "2" => "Video");
}

function categories_to_form($categories) {
    $aCategories = array();

    foreach ($categories as $key => $c) {
        $aCategories[$c->category_id] = $c->name;
    }

    return $aCategories;
}

function clean_name($name) {
    return url_title($name, '-', TRUE);
}

function all_images() {
    $CI = & get_instance();
    $CI->load->helper('directory');

    $dir = "uploads/post";
    $files = directory_map($dir);

    return $files;
}
