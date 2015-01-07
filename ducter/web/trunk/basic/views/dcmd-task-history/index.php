<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '历史任务';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-history-index">

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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'task_name', 'label'=>'任务名称', 'enableSorting'=>false, 'content'=>function($model, $key, $index, $col) { return Html::a($model['task_name'], Url::to(['dcmd-task-history/view', 'id'=>$model['task_id']])); }),
            array('attribute'=>'task_cmd', 'label'=>'任务类型', 'enableSorting'=>false,),
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'filter'=>$svr, 'enableSorting'=>false,),/// 'content'=>function($model, $key, $index, $col) { return $model->getAppName($model['svr_id']);}),
            array('attribute'=>'state', 'label'=>'状态',  'enableSorting'=>false, 'filter'=>array(0=>"未执行", 1=>"正在执行",2=>"达到失败上限停止", 3=>"完成", 4=>"完成但有未完成的服务器"), 'content'=>function($model, $key, $index, $col) {
              if($model['state'] == 0) return "未执行";
              if($model['state'] == 1) return "正在执行";
              if($model['state'] == 2) return "达到失败上限停止";
              if($model['state'] == 3) return "完成";
              if($model['state'] == 4) return "完成但有未完成的服务器";
              return "";             
            }),
            array('attribute'=>'opr_uid','enableSorting'=>false, 'label'=>'创建者'),
            array('attribute'=>'ctime','enableSorting'=>false, 'filter'=>false, 'label'=>'创建时间'),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
