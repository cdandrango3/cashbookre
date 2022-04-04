<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Retention */

$this->title = 'Create Retention';
$this->params['breadcrumbs'][] = ['label' => 'Retentions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="retention-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
