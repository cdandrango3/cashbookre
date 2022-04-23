<?php

use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\retention */
/* @var $form yii\widgets\ActiveForm */
$account = ArrayHelper::map(\app\models\ChartAccounts::find()
    ->Select(["id,concat(code,' ',slug) as name"])
    ->alias('t')
    ->where(['(select count(*) from chart_accounts t2 where t2.parent_id=t.id)'=>0])->andWhere(['parent_id'=>13252])->asArray()->all(),'id', 'name');

?>

<div class="retention-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_chart')->widget(Select2::classname(), [
            'data' => $account,
            'options'=>['id'=>'subcatid'],
             'pluginOptions'=>['placeholder'=>'Select...',
        ]]); ?>

    <?= $form->field($model, 'id_charting')->widget(Select2::classname(), [
        'data' => $account,
        'options'=>['id'=>'catid'],
        'pluginOptions'=>['placeholder'=>'Select...']]); ?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
