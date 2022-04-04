<?php

use app\models\Institution;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BankDetails */
/* @var $form yii\widgets\ActiveForm */

$type_account=ArrayHelper::map(\app\models\BankAccountType::find()->asArray()->all(), 'id', 'name');
$id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
$chart_account=ArrayHelper::map(\app\models\ChartAccounts::find()
    ->Select(["id,concat(code,' ',slug) as name"])
    ->alias('t')
    ->where(['(select count(*) from chart_accounts t2 where t2.parent_id=t.id)'=>0])->andWhere(['parent_id'=>13125])->andWhere(["institution_id"=>$id_ins->id])->asArray()->all(),'id', 'name');
     $bank_name=ArrayHelper::map(\app\models\Bank::find()->asArray()->all(), 'id', 'name');
     $city=ArrayHelper::map(\app\models\City::find()->asArray()->all(), 'id', 'cityname');
?>


<div class="bank-details-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary">
Detalles bancarios
            </div>
<div class="card-body">
    <?= $form->field($model, 'status')->checkbox() ?>
    <?php echo '<label class="control-label">Tipo de Cuenta</label>'?>
    <?=

    Select2::widget([
            'model'=>$model,
        'id' => 'typebank',
        'attribute' => 'bank_type_id',
        'name' => 'type_account',
        'data' => $type_account,
        'options' => [
            'placeholder' => 'Seleccione tipo de cuenta bancaria',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <br>
    <?= $form->field($model, 'number_account')->textInput() ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <br>
    <?php echo '<label class="control-label">Cuenta Contable</label>'?>
    <?=

    Select2::widget([
            'model'=>$model,
        'id' => 'chairbank',
        'attribute' => 'chart_account_id',
        'name' => 'accountbank',
        'data' => $chart_account,
        'options' => [
            'placeholder' => 'Seleccione la cuenta bancaria',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <br>
    <?php echo '<label class="control-label">Cuenta Bancaria</label>'?>
    <?=

    Select2::widget([
        'model'=>$model,
        'id' => 'accountbank',
        'attribute' => 'bank_id',
        'name' => 'accountbank',
        'data' => $bank_name,
        'options' => [
            'placeholder' => 'Seleccione la cuenta bancaria',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <br>
    <?php echo '<label class="control-label">Ciudad</label>'?>
    <?=

    Select2::widget([
        'model'=>$model,
        'id' => 'city',
        'attribute' => 'city_id',
        'name' => 'accountbank',
        'data' => $city,
        'options' => [
            'placeholder' => 'Seleccione la ciudad',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
</div>
        </div>

    </div>






    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
