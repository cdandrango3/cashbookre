<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use yii\grid\ActionColumn;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;
use slatiusa\treetable\Treetable;
use app\models\ChartAccounts;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChartAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
error_reporting(E_ALL ^ E_DEPRECATED);
$this->registerJsFile('@web/js/chartaccounts/index.js?v='.time(), ['depends' => \yii\web\JqueryAsset::className()]);

$this->title = 'Plan de Cuentas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);


?>
<style>
    table.treetable tr.collapsed span.indenter a {
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABnElEQVR42nXTvytFYRzH8XNch+u3xUJK2YjSHcgfIAOKFIWSRbFRykDKJplMFDEw4ZZJYjHIcCWDQQZiMRrk1+V6f93Po+Piqdc99zzneb7n+3yf5/je7+bHYrECrk94D/VHdX22n0QikR5sP0z4nqxrStc8RPAq1rLwYQFsXmYA13rRhUYU4xaHWMH5j3QVwNdbqzCFIe/vdo9pLGluyteaU+pYR7/StVTfkESuAuTomWW4g8AV7BE92NSkNw0ew6r6W9WfjxvU2TwLEFG14+iwAqlw1saxgGM0qc+CBOjGlq8CWoqXqNQ2TWjgES7QjnI0o0/Lm7dxLkAh7lCCB5T+U8RObKvgyxh2AbJxihotZ08TbNA+JtGACtQrgxnMWgBL/wWLGA2t0dog1rCLNvUl9UKryYkFiGrdtThAme5tFzZUhxFV/VW7ENdyArcEd5AGdBbCb3LtQ6mf6ZRe270fOsZZGmQP51CdUcCklmI7dBU+iV/fgj4Ol0kRWrz0t2A7c6tiniiYG+f9CKD/gd6W8n63QFl+feY25xO1RHN+gq7RjAAAAABJRU5ErkJggg==') no-repeat;

    }

    table.treetable tr.expanded span.indenter a {
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABc0lEQVR42nWTvS8EQRjGZ26tW0FolBKJzke1BYleKYiECI1GoRcNkehEqSJRaFRCosQfoFgRpSgIjVIhvtat57l7RmbX7Zv8dm7m5v14Zt6x5r/ZOI7bMb6DH2890vjBT5Ikjc38wOHPWWOmsQ0E4EvQKqDGAPQrBnA2B6bBCOgCT+ASHIDbXLkKYJW1D6yDJdPcXsAG2JNvZqU508IhWADfmlfcRpCCVklhhScgdAf2BmbBkZyN52i9CmqgCh7BMP0YINBpn4IJbQpMuTFBCGbAsdUBMuod6NU1rYFPzymU4xiYl7QdsOoCdIBnnfgr6C7JPintlLYPll2AFnANBiTnotBEFUljhUOab4ItBqiq3F2w4mkss1QJR8EVA0TSPahm6VGQoMQ50oFPuWv0r2xRveA2p1oPlJWl35hGlz5wbr02djr55zbob5L9jCcP7v1OrL8FPQ5XSScYN/m3cE7NhSYzuQD6HXqlFy1UlfUbos8vRghrfuCzbVMAAAAASUVORK5CYII=') no-repeat;
    }
</style>

<div class="chart-accounts-index">
    <div id="ajaxCrudDatatable">
        <?php Pjax::begin([
            'id'=>'crud-datatable'
        ]); ?> 
        <?=
        Treetable::widget([
            'id'=>'treetable',
            'dataProvider' => $dataProvider,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return ['data-tt-id' => $model->id, 'data-tt-parent-id' => $model->parent_id];
            },
            'treetableOptions' => ['expandable' => true, 'indent' => 19],
            'columns' => [
                [
                    'contentOptions'=>[ 'style'=>'text-align: left;width:250px'],
                    'attribute'=>'code',
                    'header'=>'<button class="btn btn-default btn-xs" title="Expandir todo" data-toggle="tooltip" onclick="toggleexpand(this)"><i class="fas fa-plus" ></i></button>',
                    'value'=>function($model){
                        return $model->childs?"<b>$model->code</b>":$model->code;
                    },
                    'format'=>'raw',
                ],
                [
                    'attribute'=>'slug',
                    'value'=>function($model){
                        return $model->childs?"<b>$model->slug</b>":$model->slug;
                    },
                    'format'=>'raw',
                ],
                [
                    'attribute'=>'balance',
                    'contentOptions'=>[ 'style'=>'text-align: right;width:200px'],
                    'value' => function ($model) {
                        setlocale(LC_MONETARY, 'en_US.UTF-8');
                        $result = '$' . number_format($model->balance, 2, '.', ',');
                        return  $model->childs?"<b>$result</b>":$result;
                    },
                    'format'=>'raw',

                ],
                [
                    'header'=>Html::a('<span class="fas fa-plus"> </span> ', Url::to(['create']), ['role' => 'modal-remote', 'title' => 'Crear cuenta raiz', 'data-toggle' => 'tooltip']),
                    'class' => ActionColumn::class,
                    'template'=>'{create}{update}{delete}{separator}{bigbook}{diarybook}',
                    'contentOptions'=>['width'=>'120px'],
                    'urlCreator' => function ($action, $model, $key, $index) {

                        return Url::to([$action, 'id' => $key]);
                    },
                    'buttons' => [
                        'create' => function ($url) {
                            return Html::a('<span class="fas fa-plus"> </span> ', $url, ['role' => 'modal-remote', 'title' => 'Crear cuenta', 'data-toggle' => 'tooltip']);
                        },
                        'update' => function ($url) {
                            return Html::a('<span class="fas fa-pen"> </span> ', $url, ['role' => 'modal-remote', 'title' => 'Editar Cuenta', 'data-toggle' => 'tooltip']);
                        }, 
                        'delete' => function ($url) {
                            return Html::a('<span class="fas fa-trash"> </span> ', $url, [
                                'role' => 'modal-remote', 'title' => 'Delete',
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'POST',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => 'Está seguro?',
                                'data-confirm-message' => 'Está seguro de querer borrar este elemento'
                            ]);
                        },
                        'separator' => function ($url) {
                            return  ' | ';
                        },
                        'bigbook' => function ($url,$model) {
                            return Html::a('<span class="fas fa-copy"> </span> ', Url::to(['bigbook','account'=>$model->id]), [ 'title' => 'Ir a Libro Mayor', 'data-toggle' => 'tooltip']);
                        },
                        'diarybook' => function ($url,$model) {
                            return Html::a('<span class="fas fa-paste"> </span> ', Url::to(['accountingseats/index','account'=>$model->id]), [ 'title' => 'Ir a Libro Diario', 'data-toggle' => 'tooltip']);
                        },
                    ],
                    'visibleButtons'=>[
                        'diarybook'=>function($model){
                            return $model->childs?false:true;
                        }
                    ],
                ],
            ]
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>