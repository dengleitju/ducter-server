<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '删除任务设备';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['dcmd-task/index']];
$this->params['breadcrumbs'][] = ['label' => '监控', 'url' => ['dcmd-task-async/monitor-task', 'task_id'=>$task_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<script>
function check() 
{  
   if(window.confirm('确认删除?')) return true;
   return false;
}
</script>

<form id="w0" method="post" onsubmit="return check()">
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

<input type='text' value='<?php echo $task_id; ?>' style="display:none" id='task_id' name='task_id'>

<div class="form-group" >
 <?= Html::submitButton('删除', ['class' => 'btn btn-success',]) ?>

</div>

<div class="dcmd-task-node-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ip', 'filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'svr_pool', 'label'=>'服务池子','filter'=>$svr_pool, 'enableSorting'=>false ),
            array('attribute'=>'ip','label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) {return $model->getAgentState($model['ip']);}),
        ],
    ]); ?>
</div>

</form>

