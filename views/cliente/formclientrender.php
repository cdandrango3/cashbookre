<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$listData=ArrayHelper::map($model,"ruc","ruc");
?>
<?php
$form = ActiveForm::begin();
?>
<?=
$form->field($model[0],"ruc")->dropDownList($listData,['prompt'=>'Select...']);
?>
<?php
echo HTML::tag("button","salvar",['class'=>'button button-primary'])
?>
<?php
ActiveForm::end();
?>



