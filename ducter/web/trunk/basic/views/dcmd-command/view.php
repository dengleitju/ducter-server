<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdCommand */

$this->title = $model->cmd_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Commands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-command-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cmd_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cmd_id], [
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
            'cmd_id',
            'task_id',
            'subtask_id',
            'svr_pool',
            'svr_pool_id',
            'svr_name',
            'ip',
            'cmd_type',
            'state',
            'err_msg',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
