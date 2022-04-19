<?php
/**/

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;dbname=tre1',
    'username' => 'postgres',
    'password' => 'barcelona97.',
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public' //specify your schema here
        ]
    ], // Postg

];

/*
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=ec2-54-157-79-121.compute-1.amazonaws.com;dbname=dfn3m087fsipl',
    'username' => 'rwrphelswemffi',
    'password' => '434801de2d2ed6e93f7674f6b2d68d4f8e7e2820851db7aa2cb97069ae9f2ae4',
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public'
        ]
    ],


];
*/




