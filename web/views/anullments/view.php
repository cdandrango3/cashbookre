<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Annulments */
?>
<div class="annulments-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'descripcion',
            'n_factura',
        ],
    ]) ?>

</div>
