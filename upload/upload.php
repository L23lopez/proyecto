<html lang="en">
<head>
    <link href="estilo.css" rel="stylesheet" type="text/css">
    <meta charset="UTF-8">
    <title>Subir archivo</title>
</head>
<body>




<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;
use Aws\Common\Aws;
use Aws\S3\Exception\S3Exception;
use Aws\Rekognition\RekognitionClient;

function main($name){
    $result = uploadFile($name);
    if($result){
        echo '<h1>Se ha subido correctamente</h1>';
        echo $_FILES['name'];
        uploadFileToBucket($result);
    } else {
      
      echo "no se ha subido";
      //  header('Location: https://informatica.ieszaidinvergeles.org:10053/pia/upload/index.html');
    }
}

main('file');



//Funcion al bucket AWS

function uploadFileToBucket($result) {
    
    $options = [
        'region' => "us-east-1",
        'version' => 'latest',
        'credentials' => [
            'key' => 'ASIA4PEOFVWDB3CEHB55',
            'secret' => 'kaoO7zF9JjGSE/cdxuL9+fa3xiMUkJ9gHic1AYZE',
            'token' => 'FwoGZXIvYXdzEIT//////////wEaDP5A6SWtVc7T2T8npyLIAQmscsI+jz3QKLDZHx1d3KEi30EddlG/YF8usjJ1IuevAOUMzJRP7Xmswfr3xEP8ogqfW9k7MdPDc0Qx/YSqCdREJf1PI3yFHkdFJ5AQE0II25vLv1UWRXx2vQZPrU97xLpMbXVuIMhLgBVW6saGPZephz5VMTgIlmcNG+URydz7llXMV6ArF0o+r3KJVoWJxrbxnunzg4ZVV1ifRYEpriUrsq2TGWukCqfMGJUFEeo4UIVltpZnqIx/Pk+cI9okaJpbEnlmSHpFKMnzwo8GMi1XXrlY9VpxjSZEvqgSF2iYxt0nu1YrYLd/mKgT8/AkERGfi5NrASAzblXd8vs='
        ]
        ];
        $file_name= $result[0];
        $file_path = $result[1];
        try{
            $s3Client = new S3Client($options);
            $result = $s3Client->putObject([
                'Bucket' => 'bucketfaces',
                'Key' => $file_name,
                'Source' => $file_path,
            ]);
            echo "<pre> ".print_r($result,true)." </pre>";
        } catch(S3Exception $e){
            echo $e->getMessage()."\n";
        }
    
    

header('Location: https://informatica.ieszaidinvergeles.org:10053/pia/upload/procesado.php?file=' . $file . '&name=' . $name);
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



</body>
</html>
