<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use hoaaah\ajaxcrud\CrudAsset;
use hoaaah\ajaxcrud\BulkButtonWidget;
use \derekisbusy\popper\PopperAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountingSeatsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
error_reporting(E_ALL ^ E_DEPRECATED);
$this->title = 'Libro Mayor';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="accounting-seats-index">
    <div class="row">
        <div class="col-sm text-right">
            <a href="" class="btn btn-default" title="Exportar a Excel" data-toggle="tooltip"> <i class="fas fa-file-excel text-green"></i></a>
            <a href="" class="btn btn-default" title="Exportar a pdf" data-toggle="tooltip"> <i class="fas fa-file-pdf text-red"></i></a>
        </div>
    </div>
    <br>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'toolbar' => [
            [
                'content' =>
                '{toggleData}' .
                    '{export}'
            ],
        ],
        'exportConfig' => [
            GridView::CSV => [
                //'label' => Yii::t('kvgrid', 'CSV'),
                //'icon' => $isFa ? 'file-code-o' : 'floppy-open',
                //'iconOptions' => ['class' => 'text-primary'],
                //'showHeader' => true,
                //'showPageSummary' => true,
                //'showFooter' => true,
                //'showCaption' => true,
                //'filename' => Yii::t('kvgrid', 'grid-export'),
                //'alertMsg' => Yii::t('kvgrid', 'The CSV export file will be generated for download.'),
                //'options' => ['title' => Yii::t('kvgrid', 'Comma Separated Values')],
                //'mime' => 'application/csv',
                //'config' => [
                    //'colDelimiter' => ",",
                    //'rowDelimiter' => "\r\n",
                //]
            ],
            
            GridView::EXCEL => [
                //'label' => Yii::t('kvgrid', 'Excel'),
                //'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
                //'iconOptions' => ['class' => 'text-success'],
                //'showHeader' => true,
                //'showPageSummary' => true,
                //'showFooter' => true,
                //'showCaption' => true,
                //'filename' => Yii::t('kvgrid', 'grid-export'),
                //'alertMsg' => Yii::t('kvgrid', 'The EXCEL export file will be generated for download.'),
                //'options' => ['title' => Yii::t('kvgrid', 'Microsoft Excel 95+')],
                //'mime' => 'application/vnd.ms-excel',
                //'config' => [
                    //'worksheet' => Yii::t('kvgrid', 'ExportWorksheet'),
                    //'cssFile' => ''
                //]
            ],
            GridView::PDF => [
                //'label' => Yii::t('kvgrid', 'PDF'),
                //'icon' => $isFa ? 'file-pdf-o' : 'floppy-disk',
                //'iconOptions' => ['class' => 'text-danger'],
                //'showHeader' => true,
                //'showPageSummary' => true,
                //'showFooter' => true,
                //'showCaption' => true,
                //'filename' => Yii::t('kvgrid', 'grid-export'),
                //'alertMsg' => Yii::t('kvgrid', 'The PDF export file will be generated for download.'),
                //'options' => ['title' => Yii::t('kvgrid', 'Portable Document Format')],
                //'mime' => 'application/pdf',
                'config' => [
                    'mode' => 'c',
                    'format' => 'A4-P',
                    'destination' => 'D',
                    'marginTop' => 20,
                    'marginBottom' => 20,
                    'cssInline' => '.kv-wrap{padding:20px;}' .
                        '.kv-align-center{text-align:center;}' .
                        '.kv-align-left{text-align:left;}' .
                        '.kv-align-right{text-align:right;}' .
                        '.kv-align-top{vertical-align:top!important;}' .
                        '.kv-align-bottom{vertical-align:bottom!important;}' .
                        '.kv-align-middle{vertical-align:middle!important;}' .
                        '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                    'methods' => [
                        'SetHeader' => [
                            //['odd' => $pdfHeader, 'even' => $pdfHeader]
                        ],
                        'SetFooter' => [
                            //['odd' => $pdfFooter, 'even' => $pdfFooter]
                        ],
                    ],
                    'options' => [
                        //'title' => $title,
                        //'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
                        //'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf')
                    ],
                    'contentBefore' => '',
                    'contentAfter' => ''
                ]
            ],
        ],
        'panel' => [
            'type' => 'primary',
            'heading' => '<i class="fas fa-list"></i> Lista de transacciones',

        ],
        'columns' => [
            [
                'header' => 'Fecha',
                'value' => function ($model) {
                    return ($model['debit'] == 'SALDO ANTERIOR') ? "<b>" . $model['date'] . "</b>" : $model['date'];
                },
                'format' => 'raw',
            ],
            [
                'header' => 'Descripción',
                'value' => function ($model) {
                    return ($model['debit'] == 'SALDO ANTERIOR') ? "<b>" . $model['description'] . "</b>" : $model['description'];
                },
                'format' => 'raw',
            ],
            [
                'header' => 'Debe',
                'contentOptions' => ['style' => 'text-align: right;'],
                'value' => function ($model) {
                    $result = is_numeric($model['debit'])?'$' . number_format($model['debit'], 2, '.', ','):'';
                    return ($model['debit'] == 'SALDO ANTERIOR') ? "<b>" . $model['debit'] . "</b>" : $result;
                },
                'format' => 'raw',
            ],
            [
                'header' => 'Haber',
                'contentOptions' => ['style' => 'text-align: right;'],
                'value' => function ($model) {
                    $result = is_numeric($model['credit'])?'$' . number_format($model['credit'], 2, '.', ','):'';
                    return ($model['debit'] == 'SALDO ANTERIOR') ? "<b>" . $model['credit'] . "</b>" : $result;
                },
                'format' => 'raw',
            ],
            [
                'header' => 'Saldo',
                'contentOptions' => ['style' => 'text-align: right;width:200px'],
                'value' => function ($model) {
                    setlocale(LC_MONETARY, 'en_US.UTF-8');
                    $result = '$' . number_format($model['balance'], 2, '.', ',');
                    return ($model['debit'] == 'SALDO ANTERIOR') ? "<b>$result</b>" : $result;
                },
                'format' => 'raw',

            ],
        ],
    ]) ?>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
    "size" => "modal-xl",
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
]) ?>
<?php Modal::end(); ?>