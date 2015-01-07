<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplate */

$this->title = '修改任务模板: ' . ' ' . $model->task_tmpt_name;
$this->params['breadcrumbs'][] = ['label' => '任务模板', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->task_tmpt_name, 'url' => ['view', 'id' => $model->task_tmpt_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-task-template-update">


    <?= $this->render('_form2', [
        'model' => $model,
        'app' => $app,
        'task_cmd' => $task_cmd,
        'svr' => $svr,
        'arg_content' => $arg_content,
    ]) ?>

</div>
