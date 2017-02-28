<?php
use App\Model\Entity\Image;

return [
    'Api.upload' => 'media' . DS . 'api_upload',
    'Api.upload_folders' => [
        Image::TYPE_IMAGE => 'media' . DS . 'api_upload' . DS . 'visual' . DS,
        Image::TYPE_AVATAR => 'media' . DS . 'api_upload' . DS . 'avatars' . DS
    ],
    'image.max.size' => 500000
];



