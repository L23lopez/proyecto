<?php

require 'vendor/autoload.php';

use Aws\Rekognition\RekognitionClient;

$newCoords = [];
if(isset($_GET['name'])) {
    $name = $_GET['name'];
    $coords = detectFaces($name);
    $newCoords = getFaceValues($coords);
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($newCoords);

function detectFaces($name) {
    try{
        $rekognition = new RekognitionClient([
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => [
                 'key' => 'ASIA4PEOFVWDB3CEHB55',
                'secret' => 'kaoO7zF9JjGSE/cdxuL9+fa3xiMUkJ9gHic1AYZE',
                'token' => 'FwoGZXIvYXdzEIT//////////wEaDP5A6SWtVc7T2T8npyLIAQmscsI+jz3QKLDZHx1d3KEi30EddlG/YF8usjJ1IuevAOUMzJRP7Xmswfr3xEP8ogqfW9k7MdPDc0Qx/YSqCdREJf1PI3yFHkdFJ5AQE0II25vLv1UWRXx2vQZPrU97xLpMbXVuIMhLgBVW6saGPZephz5VMTgIlmcNG+URydz7llXMV6ArF0o+r3KJVoWJxrbxnunzg4ZVV1ifRYEpriUrsq2TGWukCqfMGJUFEeo4UIVltpZnqIx/Pk+cI9okaJpbEnlmSHpFKMnzwo8GMi1XXrlY9VpxjSZEvqgSF2iYxt0nu1YrYLd/mKgT8/AkERGfi5NrASAzblXd8vs='
      
            ]
        ]);
        $result = $rekognition->DetectFaces(array(
            'Image' => [
                'S3Object' => [
                    'Bucket' => 'bucketface',
                    'Name' => $name,
                ],
            ],
           'Attributes' => ['ALL']
           )
        );
    } catch(Exception $e) {
        echo $e->getMessage();
        $result = false;
    }
    return $result;
}

function getFaceValues($data) {
    $faces = [];
    foreach($data['FaceDetails'] as $index => $value) {
        $face = [];
        $face['width']  = $value['BoundingBox']['Width'];
        $face['height'] = $value['BoundingBox']['Height'];
        $face['left']   = $value['BoundingBox']['Left'];
        $face['top']    = $value['BoundingBox']['Top'];
        $face['low']    = $value['AgeRange']['Low'];
        $face['high']   = $value['AgeRange']['High'];
        $face['gender'] = $value['Gender']['Value'];
        $faces[] = $face;
    }
    return $faces;
}

function getUnderAgeFaces($faces) {
    $result = [];
    foreach($faces['FaceDetails'] as $face) {
        if($face['AgeRange']['Low'] < 18) {
            $row = [];
            $row['left'] = $face['BoundingBox']['Left'];
            $row['top'] = $face['BoundingBox']['Top'];
            $row['width'] = $face['BoundingBox']['Width'];
            $row['height'] = $face['BoundingBox']['Height'];
            $result[] = $row;
        }
    }
    return $result;
} 