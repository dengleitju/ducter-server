<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePool */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Service Pools', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-service-pool-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'task_id',
            'task_cmd',
            'svr_pool',
            'svr_pool_id',
            'env_ver',
            'repo',
            'run_user',
            'undo_node',
            'doing_node',
            'finish_node',
            'fail_node',
            'ignored_fail_node',
            'ignored_doing_node',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
