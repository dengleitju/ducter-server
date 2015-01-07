<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<form id="w0" action="/dcmd/index.php?r=dcmd-task/finish-task" method="post">
<div class="dcmd-task-index">
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

   <button  type='submit' class="btn btn-success">完成任务</button> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'task_name','label'=>"任务名称", 'enableSorting'=>false,'content'=>function($model, $key, $index, $colum){return Html::a($model['task_name'], Url::to(['dcmd-task-async/monitor-task', 'task_id'=>$model['task_id']]));}),
            array('attribute'=>'task_cmd','label'=>'任务脚本名称', 'enableSorting'=>false,),
            array('attribute'=>'svr_id', 'label'=>'产品名称', 'enableSorting'=>false,),
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'enableSorting'=>false,),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false,),
            array('attribute'=>'opr_uid', 'label'=>'创建者','filter'=>false, 'enableSorting'=>false,),
            array('attribute'=>'ctime', 'label'=>'创建时间','enableSorting'=>false, 'filter'=>false,),
        ],
    ]); ?>

</div>
