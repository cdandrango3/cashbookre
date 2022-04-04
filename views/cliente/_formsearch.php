<?php

use app\models\HeadFact;
use app\models\Institution;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelhead app\models\FacturaHead*/
/* @var $form yii\widgets\ActiveForm */
$_SESSION['id_ins'] = Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
$person=ArrayHelper::map(\app\models\Person::find()
    ->where(["institution_id"=>$_SESSION['id_ins']->id])->asArray()->all(),'id', 'name');
$dat=ArrayHelper::map(HeadFact::find()->all(), 'tipo_de_documento', 'tipo_de_documento');
?>
<div class="container">
    <div class="row">
    <div class="col-6">
<?php $form = ActiveForm::begin(); ?>
<?= HTML::label("Número de Factura")?>
    <?= Html::input('text','nfac','', $options=['class'=>'form-control','maxlength'=>10,'id'=>'nfac']) ?>
        <br>
<?= HTML::label("Personas")?>
    <?=
    Select2::widget([
        'model' => $modelhead,
        'attribute' => 'id_personas',
        'name' => 'accountive',
        'value'=>$modelhead->id_personas,
        'data' => $person,
        'options' => [
            'placeholder' => 'Seleccione a la persona',
            'id' => 'personas'
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);

    ?>
    </div>
        <div class="col-6">
            <?=Html::label('Tipo de Documento')?>
            <?=
            Select2::widget([
                'model' => $modelhead,
                'attribute' => 'tipo_de_documento',
                'name' => 'account',
                'data' => $dat,
                'options' => [
                    'placeholder' => 'Seleccione tipo de documento',
                    'id' => 'Tipo'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);

            ?>
        </div>
    </div>
</div>





    <div class="form-group">

    </div>

    <?php ActiveForm::end(); ?>

