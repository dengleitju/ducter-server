<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolNode */

$this->title = 'Update Dcmd Service Pool Node: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Service Pool Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-service-pool-node-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
