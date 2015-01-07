<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskNodeHistory */

$this->title = $model->subtask_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Node Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-node-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->subtask_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->subtask_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'subtask_id',
            'task_id',
            'task_cmd',
            'svr_pool',
            'svr_name',
            'ip',
            'state',
            'ignored',
            'start_time',
            'finish_time',
            'process',
            'err_msg',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
