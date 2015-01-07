<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmdArg */

$this->title = '修改任务脚本参数: ' . ' ' . $model->arg_name;
$this->params['breadcrumbs'][] = ['label' => '任务脚本', 'url' => ['dcmd-task-cmd/index']];
$this->params['breadcrumbs'][] = ['label' => $model->task_cmd, 'url' => ['dcmd-task-cmd/view', 'id' => $model->task_cmd_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-task-cmd-arg-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
