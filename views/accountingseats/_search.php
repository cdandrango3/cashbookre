<?php

use app\models\Institution;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ChartAccounts;
use app\models\CostCenter;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\AccountingSeatsSearch */
/* @var $form yii\widgets\ActiveForm */
$_SESSION['id_ins'] = Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
$accountdata = ArrayHelper::map(ChartAccounts::find()->Select(["id,concat(code,' ',slug) as name"])->Where(['institution_id' => $_SESSION['id_ins']->id])->orderBy('code,parent_id')->asArray()->all(), 'id', 'name');
$costcenterdata = ArrayHelper::map(CostCenter::find()->Select(["id","name"])->Where(['institution_id' => $model->institution_id])->orderBy('name')->asArray()->all(), 'id', 'name');
?>

<div class="accounting-seats-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Búsqueda
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    Cuenta
                </div>
                <div class="col-3">
                    <?=
                    Select2::widget([
                        'id' => 'account',
                        'name' => 'AccountingSeats[account]',
                        'data' => $accountdata,
                        'value' => $model->account,
                        'options' => [
                            'placeholder' => 'Seleccione cuenta',
                            'class' => 'account',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-1">
                    Fecha Desde
                </div>
                <div class="col-2">
                    <?= DatePicker::widget([
                        'name' => 'AccountingSeats[datefrom]',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => '2022-01-01',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-1">
                    Fecha Hasta
                </div>
                <div class="col-2">
                <?= DatePicker::widget([
                        'name' => 'AccountingSeats[dateto]',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => $model->dateto,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    Centro de Costo
                </div>
                <div class="col-3">
                    <?=
                    Select2::widget([
                        'id' => 'costcenter',
                        'name' => 'AccountingSeats[cost_center]',
                        'data' => $costcenterdata,
                        'value' => $model->cost_center,
                        'options' => [
                            'placeholder' => 'Seleccione centro de costo',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-3">

                </div>
                <div class="col-3">

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>