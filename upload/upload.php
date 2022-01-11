<?php
require 'vendor/autoload.php';

main('archivo');


function main($name){
    $subida = uploadFile($name);
    if($subida){
        echo 'Se ha subido correctamente';
    } else {
        header('location' . $server['HTTP_REFERER']);
    }
}


function isUploadedFile($name) {
    return isset($_FILES[$name]);
}

function isValidUploadedFile($file) {
    $result = true;
    $error = $file['error'];
    $name = $file['name'];
    $size = $file['size'];
    $tmp_name = $file['tmp_name'];
    $type = $file['type'];
    if($error != 0 || $name == '' || $size == 0 || strpos($type, 'image/') === false || !is_uploaded_file($tmp_name)) {
        $result = false;
    } else {
        $mcType = mime_content_type($tmp_name);
        if(strpos($mcType, 'image/') === false) {
            $result = false;
        }
    }
    return $result;
}

function moveFile($file) {
    $target = 'originales';
    $uniqueName = uniqid('imagen_');
    $name = $file['name'];
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $tmp_name = $file['tmp_name'];
    $uploadedFile = $target . '/' . $uniqueName . '.' . $extension;
    if(move_uploaded_file($tmp_name, $uploadedFile)) {
        return [$uploadedFile, $uniqueName . '.' . $extension, $uniqueName, $extension,];
    }
    echo 'No se ha podido hacer la subida';
    return false;
}


function uploadFile($paramName) {
    $result = false;
    if(!isUploadedFile($paramName)) {
        return false;
    }
    $file = $_FILES[$paramName];
    if(!isValidUploadedFile($file)) {
        return false;
    }
    return moveFile($file);
}


?>

