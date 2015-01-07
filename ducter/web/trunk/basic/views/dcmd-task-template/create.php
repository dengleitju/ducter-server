<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplate */

$this->title = '添加任务模板';
$this->params['breadcrumbs'][] = ['label' => '任务模板', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-template-create">


    <?= $this->render('_form', [
        'model' => $model,
        'app' => $app,
        'task_cmd' => $task_cmd,
        'svr' => array(),
    ]) ?>

</div>
