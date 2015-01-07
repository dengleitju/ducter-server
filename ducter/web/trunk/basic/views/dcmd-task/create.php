<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTask */

$this->title = '创建任务';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-create">

    <?= $this->render('_form', [
        'searchModel' => $searchModel, 
        'dataProvider' => $dataProvider,
        'model' => $model,
        'app' => $app,
        'args' => $args,
        'task_cmd_prv' => $task_cmd_prv,
    ]) ?>

</div>
