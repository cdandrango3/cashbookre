<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\retention */
?>
<div class="retention-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'percentage',
            'codesri',
            'slug',
        ],
    ]) ?>

</div>
