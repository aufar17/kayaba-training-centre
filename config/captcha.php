<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],

    'default' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 100,
        'math' => false,
        'expire' => 300,
        'encrypt' => false,
        'bgColor' => '#ffffff',
        'fontColors' => ['#000000'],
        'contrast' => -5,
    ],
    'math' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
        'length' => 5,
        'width' => 160,
        'height' => 46,
        'quality' => 100,
        'lines' => 1,
        'bgImage' => false,
        'bgColor' => '#ffffff',
        'fontColors' => ['#000000'],
        'contrast' => -5,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ],

    'distortion' => false,
    'shuffle' => false,
    'lines' => 3,
];
