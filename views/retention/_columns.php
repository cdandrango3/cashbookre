<?php
use yii\helpers\Url;

return [

    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_chart',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'codesri',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'slug',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_charting',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'type',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{update}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],

    ],

];   