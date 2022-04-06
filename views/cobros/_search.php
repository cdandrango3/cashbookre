<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Charge*/
/* @var $form yii\widgets\ActiveForm */

$person=ArrayHelper::map(\app\models\Person::find()
    ->asArray()->all(),'id', 'name');
?>
<div class="card">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-header bg-primary">
        Buscar
    </div>
    <div class="card-body">
        <?= $form->field($model, 'n_document')->textInput(["id"=>"nfac"])->label("Número de Documentos") ?>

        <?=HTML::tag("label","Persona");?>
        <?=

        Select2::widget([
            'model' => $model,
            'attribute' => 'person_id',
            'name' => 'accountive',
            'value'=>"",
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






        <div class="form-group">

        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>