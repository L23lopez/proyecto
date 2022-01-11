<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

main('fileToUpload');

// Instantiate an Amazon S3 client.
$bucket = 'proyect-upload-image-php';
$key = basename($file_Path);
$name = $key;
$file_Path = __DIR__ . '/var/www/html/pia/upload/originales'. 'prueba.jpg';

$s3Client = new S3Client([
        'profile' => 'default',
        'version' => 'latest',
        'region'  => 'us-east-1',
        'credentials' => [
            'key'    => 'ASIA3BIEX5JRYSIEX2HX',
            'secret' => 'pOyLtZ9RErNXZ2h3jLTnE6hqOxa37TC8WCSJvNRW',
            'token'  => 'FwoGZXIvYXdzEKL//////////wEaDE4KCMqDxGNXDagpmSLPAZ1qZzgXBAWNwlhX0dsiFarcUSnfqYwPp2OOoF3xDvrfir1U87AanqCcguogLjT6oBoZTyVELjlGlFvt4WQgYUxIyx2q8eWcIyPoJHmvd53JUSxak9+/nP76dhxnGDIadD9w/ksXFsu1s0sPzumLIuf8ex2r5l0KrnZFhlIRVZ5gPhNIx42bqr3bEOiV8qFMYZIIO3WxnznXTtjSSBiXhUmJbRa8Don98afxb1InbrTiH2Ep6RsZEV/+Qd5OEroOJ0V5Q4OcGcfK5Kvu3i9/VyjDqeiNBjIt4ajnievqxInI3iqnDVeF/Xu+yBLBMUZ4FR49NnYrnbtTED/O+CK+PchRq4QJ'
        ]
]);

try {
    // $result = $s3Client->putObject([
        // 'Bucket' => $bucket,
        // 'Key'    => $key,
        // 'Body'   => fopen($file_Path, 'r'),
        // 'ACL'    => 'public-read', // make file 'public'
    // ]);
    // echo "Image uploaded successfully. Image path is: ". $result->get('ObjectURL');
} catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e->getMessage();
}

function main($name) {
    $result = uploadFile($name);
    if($result) {
        echo 'todo bien';
    } else {
        header('location: ' . $_SERVER['HTTP_REFERER']);
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
    $uniqueName = uniqid('image_');
    $name = $file['name'];
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $tmp_name = $file['tmp_name'];
    $uploadedFile = $target . '/' . $uniqueName . '.' . $extension;
    if(move_uploaded_file($tmp_name, $uploadedFile)) {
        return [$uploadedFile, $uniqueName . '.' . $extension, $uniqueName, $extension,];
    }
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