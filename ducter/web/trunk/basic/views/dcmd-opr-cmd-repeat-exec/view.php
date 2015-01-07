<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExec */

$this->title = $model->repeat_cmd_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmd Repeat Execs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-repeat-exec-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->repeat_cmd_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->repeat_cmd_id], [
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
            'repeat_cmd_id',
            'repeat_cmd_name',
            'opr_cmd',
            'run_user',
            'timeout:datetime',
            'ip:ntext',
            'repeat',
            'cache_time:datetime',
            'ip_mutable',
            'arg_mutable',
            'arg:ntext',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
