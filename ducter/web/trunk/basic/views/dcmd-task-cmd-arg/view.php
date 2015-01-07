<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmdArg */

$this->title = $model->arg_name;
$this->params['breadcrumbs'][] = ['label' => '任务脚本', 'url' => ['dcmd-task-cmd/index']];
$this->params['breadcrumbs'][] = ['label' => $model->task_cmd, 'url' => ['dcmd-task-cmd/view', 'id'=>$model->task_cmd_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-cmd-arg-view">

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <!--    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'arg_name', 'label'=>'参数名称'),
            array('attribute'=>'optional', 'label'=>'是否可选','content'=>function($model, $key, $index,$column) {if($model['optional'] == 0) return "否"; return "是";}),
            array('attribute'=>'arg_type', 'label'=>'参数类型', 'content'=>function($model, $key, $index,$column) {if($model['arg_type'] == 1) return "int"; if($model['arg_type'] ==2) return "float"; if($model['arg_type'] ==3) return "bool"; if($model['arg_type'] == 4) return "char"; if($model['arg_type'] == 5) return "datatime";},),
        ],
    ]) ?>

</div>
