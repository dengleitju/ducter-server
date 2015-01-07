<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */

$this->title = '修改任务脚本: ' . ' ' . $model->task_cmd;
$this->params['breadcrumbs'][] = ['label' => '任务脚本', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->task_cmd, 'url' => ['view', 'id' => $model->task_cmd_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-task-cmd-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
