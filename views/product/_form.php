<?php

use app\models\Institution;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\product */
/* @var $form yii\widgets\ActiveForm */
$id_ins=Institution::findOne(['users_id'=>Yii::$app->user->identity->id]);
$accountdata = ArrayHelper::map(\app\models\ChartAccounts::find()
    ->Select(["id,concat(code,' ',slug) as name"])
    ->alias('t')
    ->where(['(select count(*) from chart_accounts t2 where t2.parent_id=t.id)'=>0])->andWhere(['institution_id'=>$id_ins->id])->asArray()->all(),'id', 'name');

$accountdataingresos = ArrayHelper::map(\app\models\ChartAccounts::find()
    ->Select(["id,concat(code,' ',slug) as name"])
    ->alias('t')
    ->where(['(select count(*) from chart_accounts t2 where t2.parent_id=t.id)'=>0])->andWhere(['parent_id'=>13364])->andWhere(['institution_id'=>$id_ins->id])->asArray()->all(),'id', 'name');

Yii::debug($accountdataingresos);
$listpr=ArrayHelper::map($model2,"name","name");
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="card">
            <div class="card-header bg-primary">
                Productos y Servicios
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-12 ">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'status')->checkbox() ?>
                <?=$form->field($model2[0],"name")->dropDownList($listpr,['prompt'=>'Select...','readonly'=>false,'id'=>'listpr'])->label("tipo");?>

                <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-12 ">
                <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'product_iva_id')->dropDownList(['12' => '12%', '0' => '0%'], ['prompt' => 'Seleccione una opción']); ?>

                <?= $form->field($model, 'precio')->textInput() ?>

                <?= $form->field($model, 'costo')->textInput(['id'=>'listpro','readonly' => false]) ?>

                    </div>
                </div>
                </div>
            </div>
    <div class="card">
        <div class="card-header bg-primary">
            Contabilidad
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-12 ">

                    <?=HTML::tag("label","Ingresos")?>
                    <?=
                    Select2::widget([
                        'model' => $model,
                        'attribute' => 'charingresos',
                        'id' => 'accountingresos',
                        'name' => 'accountingresos',
                        'data' => $accountdataingresos ,
                        'options' => [
                            'placeholder' => 'Seleccione una cuenta contable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>



                    <br>
                    <br>
                    <?=HTML::tag("label","Activos")?>

                    <?=
                    Select2::widget([
                        'model' => $model,
                        'attribute' => 'chairaccount_id',
                        'id' => 'accountd',
                        'name' => 'account_data',
                        'data' => $accountdata,
                        'options' => [
                            'placeholder' => 'Seleccione una cuenta contable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                    <br>
                    <br>
                    <div class="inve">
                    <?=HTML::tag("label","Inventarios")?>
                    <?=

                    Select2::widget([
                        'model' => $model,
                        'attribute' => 'Chairinve',
                        'name' => 'accountive',
                        'data' => $accountdata,
                        'options' => [
                            'placeholder' => 'Seleccione una cuenta contable',
                            'id' => 'account'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                    </div>
                </div>
                </div>
            </div>
</div>
        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>
                <?php
                $js= <<< JS
               $('#listpr').change(function(){
                        
                      c=$(this).val();
                      
                      if(c=='servicio'){
                      $('#account').parent().hide();
                      $('#listpro').val('0');
                          
                      }
                      if(c=='producto'){
                       $('#account').parent().show();
                       $('#account').show();
                      $('#listpro').attr({
                      
                      });
                      }
                     });
JS;
                $this->registerJs($js, View::POS_READY);
                ?>


                <?php ActiveForm::end(); ?>
            </div>
        </div>


</div>
<script>


</script>
