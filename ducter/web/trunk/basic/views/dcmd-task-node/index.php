<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务设备信息';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['dcmd-task/index']];
$this->params['breadcrumbs'][] = ['label' => '监控', 'url' => ['dcmd-task-async/monitor-task', 'task_id'=>$task_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<form id="w0" action="/dcmd/index.php?r=dcmd-task-node/exec-subtasks" method="post">

<div class="dcmd-task-node-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'ip',  'enableSorting'=>false),
            array('attribute'=>'svr_pool', 'label'=>'服务池子','filter'=>$svr_pool, 'enableSorting'=>false),
            array('attribute'=>'ip','label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) {return $model->getAgentState($model['ip']);}),
            array('attribute'=>'start_time', 'label'=>'启动时间','enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'finish_time', 'label'=>'执行时间', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return strtotime($model['finish_time']) - strtotime($model['start_time']);}),
            array('attribute'=>'ignored', 'label'=>'忽略', 'enableSorting'=>false, 'filter'=>array(0=>"否", 1=>"是"),'content'=>function($model, $key, $index, $col) {if($model['ignored']==1) return "是"; else return "否";}),
            array('attribute'=>'process', 'label'=>'进度','enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'state', 'label'=>'状态', 'filter'=>array(0=>'未启动', 1=>'执行中', 2=>'完成', 3=>'失败') ,'enableSorting'=>false,  'content'=>function($model, $key, $index, $col) {if($model['state'] == 0) return "未启动"; if($model['state'] == 1) return "执行中"; if($model['state']==2) return "完成"; if($model['state']==3) return "失败"; return "error";}),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) {
  if($model['state'] == 0) return "<a href=\"javascript:void(0);\" style=\"margin:0px 2px 0px 2px\" onclick=cmdSubmit(\"task_id=".$model['task_id']."&subtask_id=".$model['subtask_id']."&cmd_type=9\")>执行</a>";
  if($model['state'] == 1) return "<a href=\"javascript:void(0);\" style=\"margin:0px 2px 0px 2px\" onclick=cmdSubmit('task_id=".$model['task_id']."&subtask_id=".$model['subtask_id']."&cmd_type=7') >终止</a>";
  if($model['state'] == 2 || $model['state'] == 3) return "<a  href=\"javascript:void(0);\" style=\"margin:0px 2px 0px 2px\" onclick=cmdSubmit(\"task_id=".$model['task_id']."&subtask_id=".$model['subtask_id']."&cmd_type=12\") >重做</a>";
}),
        ],
    ]); ?>
</div>
</form>

<script>
var cmdSubmit = function(val) {
  if (!confirm('确定执行此操作？')) {
    return false;
  }
  $.get("index.php?r=dcmd-task-async/exec-subtask-cmd&"+val+"&randt="+new Date().getTime(), function(data) {
     alert(data);
  });
}

</script>


