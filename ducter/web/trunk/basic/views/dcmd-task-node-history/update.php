<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskNodeHistory */

$this->title = 'Update Dcmd Task Node History: ' . ' ' . $model->subtask_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Node Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subtask_id, 'url' => ['view', 'id' => $model->subtask_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-task-node-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
