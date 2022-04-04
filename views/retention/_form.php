<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Retention */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="retention-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_chart')->textInput() ?>

    <?= $form->field($model, 'percentage')->textInput() ?>

    <?= $form->field($model, 'codesri')->textInput() ?>

    <?= $form->field($model, 'slug')->textInput() ?>

    <?= $form->field($model, 'id_charting')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
