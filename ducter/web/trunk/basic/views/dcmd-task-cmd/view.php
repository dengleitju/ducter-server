<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */

$this->title = $model->ui_name;
$this->params['breadcrumbs'][] = ['label' => '任务脚本', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-cmd-view">
    <?php
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => "<font color=red>".Yii::$app->getSession()->getFlash('error')."</font>",
        ]);
    }
    ?>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->task_cmd_id], ['class' => 'btn btn-primary']) ?>
        <!--<?= Html::a('Delete', ['delete', 'id' => $model->task_cmd_id], [
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
            array('attribute'=>'ui_name', 'label'=>'任务名称'),
            array('attribute'=>'task_cmd', 'label'=>'脚本名称'),
            array('attribute'=>'script_md5', 'label'=>'脚本MD5'),
            'timeout:text:超时时间',
            array('attribute'=>'comment', 'label'=>'说明'),
        ],
    ]) ?>
   <p>
    <?= Html::a('添加参数', ['dcmd-task-cmd-arg/create', 'task_cmd_id' => $model->task_cmd_id], ['class' => 'btn btn-primary']) ?>
   </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'arg_name', 'label'=>'参数名', 'filter'=>false, 'enableSorting'=>false, 'content'=>function($model, $key, $index, $column) { return Html::a($model['arg_name'], Url::to(['dcmd-task-cmd-arg/update', 'id'=>$model['id']]));}),
            array('attribute'=>'optional', 'label'=>'是否可选',  'filter'=>false, 'enableSorting'=>false, 'content'=>function($model, $key, $index, $colum) { if($model['optional'] == 0) return "否"; return  "是";}),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}','urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-task-cmd-arg/delete', 'id'=>$model['id'], 'task_cmd_id'=>$model['task_cmd_id']]);}],
        ],
    ]); ?>
</div>
