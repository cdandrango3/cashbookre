<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Annulments */
?>
<div class="annulments-view">
 <?php $fac=\app\models\HeadFact::findOne(["n_documentos"=>$model->n_factura]);
 ?>
<?php $person=\app\models\Person::findOne(["id"=>$fac->id_personas]) ?>
    <?= 
    
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'descripcion',
            'n_factura',
        [                      // the owner name of the model
            'label' => 'Fecha de emision',
            'value' => Yii::$app->formatter->asDateTime($fac->f_timestamp,'php:m/d/Y')
        ],
        [                      // the owner name of the model
            'label' => 'Persona',
            'value' => $person->name,
        ],
            [                      // the owner name of the model
                'label' => 'Tipo de Factura',
                'value' => $fac->tipo_de_documento,
            ],
            [                      // the owner name of the model
                'label' => 'Autorizacion',
                'value' => $fac->autorizacion,
            ],
        ],
    ]); ?>

</div>
