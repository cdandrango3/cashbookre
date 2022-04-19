<?php

use app\models\ProductType;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\product */
/* @var $model2 app\models\product_type */
$this->title = 'Actualizar Producto: ' . $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
$type=ProductType::find()->select("name")->all();
?>
<div class="product-update">

   

    <?= $this->render('_form', [
        'model' => $model,'model2'=>$type
    ]) ?>

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
                       $('#ingres').parent().show();
                       $('#activos').text("Inventario");
                            $('#linventario').text("Egresos");
                       $('#account').show();
                      $('#listpro').attr({
                      
                      });
                      }
                     });
                    $('#opt').change(function(){
                        fa=$('#listpr').val();
                        ha=$(this).val();
                        if(fa=="servicio" && ha=="egresos"){
                            $('#ingres').parent().hide();
                            $('#activos').text("Pasivos");
                            $('#linventario').text("Egresos");
                            $('#account').parent().show();
                        }
                    })
JS;
$this->registerJs($js, View::POS_READY);
?>