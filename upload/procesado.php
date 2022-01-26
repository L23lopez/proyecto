<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\ObjectUploader;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;
use Aws\Common\Aws;
use Aws\S3\Exception\S3Exception;
use Aws\Rekognition\RekognitionClient;


function detectFaces($file) {
    
    
    $bucketName ='bucketfaces';
	$key ='ASIA4PEOFVWDB3CEHB55';
	$secret_key = 'kaoO7zF9JjGSE/cdxuL9+fa3xiMUkJ9gHic1AYZE';
	$token ='FwoGZXIvYXdzEIT//////////wEaDP5A6SWtVc7T2T8npyLIAQmscsI+jz3QKLDZHx1d3KEi30EddlG/YF8usjJ1IuevAOUMzJRP7Xmswfr3xEP8ogqfW9k7MdPDc0Qx/YSqCdREJf1PI3yFHkdFJ5AQE0II25vLv1UWRXx2vQZPrU97xLpMbXVuIMhLgBVW6saGPZephz5VMTgIlmcNG+URydz7llXMV6ArF0o+r3KJVoWJxrbxnunzg4ZVV1ifRYEpriUrsq2TGWukCqfMGJUFEeo4UIVltpZnqIx/Pk+cI9okaJpbEnlmSHpFKMnzwo8GMi1XXrlY9VpxjSZEvqgSF2iYxt0nu1YrYLd/mKgT8/AkERGfi5NrASAzblXd8vs=';

	
    try{
	  $options = [
				'credentials' => array(
					'key' => 'ASIA4PEOFVWDB3CEHB55',
                    'secret' => 'kaoO7zF9JjGSE/cdxuL9+fa3xiMUkJ9gHic1AYZE',
                    'token' => 'FwoGZXIvYXdzEIT//////////wEaDP5A6SWtVc7T2T8npyLIAQmscsI+jz3QKLDZHx1d3KEi30EddlG/YF8usjJ1IuevAOUMzJRP7Xmswfr3xEP8ogqfW9k7MdPDc0Qx/YSqCdREJf1PI3yFHkdFJ5AQE0II25vLv1UWRXx2vQZPrU97xLpMbXVuIMhLgBVW6saGPZephz5VMTgIlmcNG+URydz7llXMV6ArF0o+r3KJVoWJxrbxnunzg4ZVV1ifRYEpriUrsq2TGWukCqfMGJUFEeo4UIVltpZnqIx/Pk+cI9okaJpbEnlmSHpFKMnzwo8GMi1XXrlY9VpxjSZEvqgSF2iYxt0nu1YrYLd/mKgT8/AkERGfi5NrASAzblXd8vs='
       
				),
		'version' => 'latest',
		'region'  => 'us-east-1'
			
    ];

    $rekognition = new RekognitionClient($options);
    
     $result = $rekognition->DetectFaces(array(
      'Image' => [
                'S3Object' => [
                    'Bucket' => $bucketName,
                    'Name'  =>  'prueba.jpg',
                ],
            ],
      'Attributes' => array('ALL')
      )
    );
    
    }catch (S3Exception $e) {
		die('Error:' . $e->getMessage());
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
	}
	
	 global $myJson;
    $myJson = json_encode($result['FaceDetails']);
	
}


detectFaces($name);


?>
