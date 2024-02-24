<?php

use Aws\Laravel\AwsServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration options set in this file will be passed directly to the
    | `Aws\Sdk` object, from which all client objects are created. The minimum
    | required options are declared here, but the full set of possible options
    | are documented at:
    | http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html
    |
    */

    'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-2'),
    'collection_id' => env('AWS_REKOGNITION_COLLECTION', 'annaTestUserFaces'),
    'bucket' => env('AWS_REKOGNITION_BUCKET'),
    'version' => 'latest',
    'ua_append' => [
        'L5MOD/' . AwsServiceProvider::VERSION,
    ],
    'url' => '//s3-' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_REKOGNITION_BUCKET') .'/public/',
    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],
];
