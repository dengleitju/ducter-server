<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTask */

$this->title = $model->task_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->task_id], [
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
            'task_id',
            'task_name',
            'task_cmd',
            'depend_task_id',
            'depend_task_name',
            'svr_id',
            'svr_name',
            'svr_path',
            'tag',
            'update_env',
            'update_tag',
            'state',
            'freeze',
            'valid',
            'pause',
            'err_msg',
            'concurrent_rate',
            'timeout:datetime',
            'auto',
            'process',
            'task_arg:ntext',
            'comment',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
