<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

main('');

// Instantiate an Amazon S3 client.
$bucket = 'subir-imagen';
$key = basename($file_Path);
$name = $key;
$file_Path = __DIR__ . '/var/www/html/pia/upload/originales'. 'imagen_61dd6580acb20.jpg';

$s3Client = new S3Client([
        'profile' => 'default',
        'version' => 'latest',
        'region'  => 'us-east-1',
        'credentials' => [
            'key'    => '',
            'secret' => '',
            'token'  => ''
        ]
]);

try {
    $result = $s3Client->putObject([
        'Bucket' => $bucket,
        'Key'    => $key,
        'Body'   => fopen($file_Path, 'r'),
        'ACL'    => 'public-read', // make file 'public'
    ]);
    echo "Image uploaded successfully. Image path is: ". $result->get('ObjectURL');
} catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e->getMessage();
}


//Funcion al bucket AWS

function uploadFileToBucket($file, $key) {
    $result = false;
    try {
        $s3 = new S3Client([
            'version'     => 'latest',
            'region'      => 'us-east-1', //depends on the value of your region
            'credentials' => [
                'key'    => getEnvironmentKey(),
                'secret' => getEnvironmentSecret(),
                'token'  => getEnvironmentToken()
            ]
        ]);
        $uploader = new MultipartUploader($s3, $file, [
            'bucket' => getBucketName(),
            'key'    => $key,
        ]);
        $result = $uploader->upload();
    } catch(MultipartUploadException $e) {
        //to see the message: $e->getMessage()
    } catch (S3Exception $e) {
        //to see the message: $e->getMessage()
    }
    return $result;
}

//Funciones de subida al server

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