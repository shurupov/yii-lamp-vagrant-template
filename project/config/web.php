<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'whatisgood',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
            ]
        ],

        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                ],

            ],
            'defaultExtension' => /*'php'*/'twig'
        ],


        'assetManager' => array(
            'converter'=>array(
                'class'=>'nizsheanez\assetConverter\Converter',
                'force'=>true, // true : If you want convert your sass each time without time dependency
                'destinationDir' => 'assets', //at which folder of @webroot put compiled files
                'parsers' => array(
                    'less' => array( // file extension to parse
                        'class' => 'nizsheanez\assetConverter\Less',
                        'output' => 'css', // parsed output file type
                        'options' => array(
                            'auto' => true, // optional options
                        )
                    )
                )
            )
        ),

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['192.168.*.*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['192.168.*.*']
    ];
}

return $config;
