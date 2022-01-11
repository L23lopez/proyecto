<?php

   require '/vendor/autoload.php';

// Instantiate an Amazon S3 client.
$bucket = 'subir-imagen';
$key = basename($file_Path);
$name = $key;
$file_Path = __DIR__ . '/upload/upload/'. 'image_61dafa2b671b7.jpg';

$s3Client = new S3Client([
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