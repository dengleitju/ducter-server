<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdCenter */

$this->title = 'Update Dcmd Center: ' . ' ' . $model->host;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->host, 'url' => ['view', 'id' => $model->host]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-center-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
