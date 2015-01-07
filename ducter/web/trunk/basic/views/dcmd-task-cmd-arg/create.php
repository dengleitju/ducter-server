<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmdArg */

$this->title = '添加任务脚本参数';
$this->params['breadcrumbs'][] = ['label' => '任务脚本', 'url' => ['dcmd-task-cmd/index', 'id'=>$task_cmd_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-cmd-arg-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
