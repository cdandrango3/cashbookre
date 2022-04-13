<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$id=$_GET["id"]
?>
<div class="card">

    <div class="card-header bg-blue">
        <h4>Anulacion</h4>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <?=$form->field($model,"descripcion")->textarea() ?>
        <?=Html::submitButton('Guardar', ['class' => 'btn btn-success float-right ','id'=>"buttonsubmit"]) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
?>

