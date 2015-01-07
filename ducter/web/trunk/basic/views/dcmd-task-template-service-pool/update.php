<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplateServicePool */

$this->title = 'Update Dcmd Task Template Service Pool: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Template Service Pools', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-task-template-service-pool-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
