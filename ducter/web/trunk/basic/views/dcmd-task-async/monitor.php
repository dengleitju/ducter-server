<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务监控';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['dcmd-task/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
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

<!---任务基本信息 --->
<div class="dcmd-app-index">
<table class="table table-striped table-bordered">
<tbody><tr><td width=15%>操作:</td><td>
 
<?php
$start_state = "";
if ($task->freeze == 1 || $task->state != 0) $start_state = "disabled";

$pause_state = "";
$pause_show = "";
if ($task->freeze == 1 || $task->state == 0 || $task->pause == 1) {
  $pause_state = "disabled";
  $pause_show = "none";
}

$resume_state =  "";
$resume_show = "";
if ( $task->freeze == 1 || $task->pause == 0)
{
   $resume_state = "disabled"; 
   $resume_show = "none";
}

if($resume_show=="none" && $pause_show == "none")
{
  $pause_show = "";
}

$retry_state = "";
if ($task->freeze == 1 ) $retry_state = "disabled";

$redo_state = "";
if ($task->freeze == 1 ) $redo_state = "disabled";

$freeze_state = "";
if ($task->freeze == 1) $freeze_state = "none"; 

$unfreeze_state = "";
if ($task->freeze == 0) $unfreeze_state = "none";

$disabled = "";
if($task->freeze == 1) $disabled = "disabled";
?>

<?= Html::a('启动', ['start-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定启动任务?',
                'method' => 'post',
            ],
            $start_state => true,
 ]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
<?=  Html::a('暂停', ['pause-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定暂停?',
                'method' => 'post',
            ],
            $pause_state =>  true,
            'style' => 'display:'.$pause_show,
]) ?>

<?= Html::a('继续', ['resume-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定继续?',
                'method' => 'post',
            ],
            $resume_state => true,
            'style' => 'display:'.$resume_show,
]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
 <?= Html::a('重做', ['redo-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定重做任务?',
                'method' => 'post',
            ],
            $redo_state =>  true,
 ]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
 <?= Html::a('重试', ['retry-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定重试?',
                'method' => 'post',
            ],
            $retry_state => true,
 ]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
 <?= Html::a('冻结', ['freeze-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定冻结',
                'method' => 'post',
            ],
            #$freeze_state => true,
            'style' => 'display:'.$freeze_state,
 ]) ?>

 <?= Html::a('解冻', ['unfreeze-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定解冻',
                'method' => 'post',
            ],
            'style'=>'display:'.$unfreeze_state,// => true,
 ]) ?> 
</td></tr>
<tr><td>任务名称:</td><td><?php echo $task->task_name; ?></td></tr>
<tr><td>任务状态:</td><td><?php if($task->valid == 0) echo "<font color=red>无效 : [".$task->err_msg."]</font>"; else if($task->freeze == 1) echo "冻结"; else if ($task->pause == 1) echo "暂停"; else if($task->state == 0) echo "未执行"; else if($task->state == 1) echo "正在执行"; else if ($task->state == 2) echo "达到失败上限停止"; else if( $task->state = 3) echo "完成"; else if ($task->state = 4) echo "完成但有未完成的服务器"; ?></td></tr>
<tr><td>产品名称:</td><td><?php echo Html::a($app_name, Url::to(['dcmd-app/view', 'id'=>$app_id]), ['target'=>'_blank']); ?></td></tr>
<tr><td>服务名称:</td><td><?php echo Html::a($task->svr_name,  Url::to(['dcmd-service/view', 'id'=>$task->svr_id]), ['target'=>'_blank']); ?></td></tr>
<tr><td>任务脚本:</td><td><?php echo $task->task_cmd; ?></td></tr>
<tr><td>版本号:</td><td><?php echo $task->tag; ?></td></tr>
<tr><td>更新环境:</td><td><?php if($task->update_env == 0) echo "否"; else if($task->update_env == 1) echo "是"; ?></td></tr>
<tr><td>输出进度:</td><td><?php if($task->process == 0) echo "否"; else if ($task->process == 1 ) echo "是"; ?></td></tr>
<tr><td>参数信息:</td><td><?php echo $args; ?></td></tr>
</tbody></table>

<!---可修改参数信息--->
<form id="w0" action="" method="post">
<table class="table table-striped table-bordered">
<tbody>  <tr>
<td width="8%">发送超时时间<font color="#FF0000">*</font></td>
<td width="13%">
<input name="timeout" type="text" value="<?php echo $task->timeout; ?>"></td>
<td width="13%">是否自动执行</td>
<td width="8%"><select name="auto" id="Auto" style="width:50px"><option value="1" <?php if($task->auto == 1) echo "selected"; ?> >是</option><option value="0" <?php if($task->auto == 0) echo "selected"; ?> >否</option></select></td>
</tr><tr><td width="13%">执行比例<font color="#FF0000">*</font></td>
<td width="12%"><input name="concurrent_rate" type="text"  value="<?php echo $task->concurrent_rate; ?>" /></td>
<td width="9%"></td>
<td width="13%">
<?= Html::submitButton('更新', ['class' => 'btn btn-primary', 'align'=>'right', $disabled=>true])?>
</td>
</tr></tbody></table>
</form>

<!---任务服务池子信息-->
<div id="TaskAppPool"></div>
<!---任务执行状况信息--->
<div id="TaskState"></div>
</div>
<script src="/dcmd/assets/7d43d3e8/jquery.js"></script>
<script src="/dcmd/assets/562e379f/yii.js"></script>
<script>
///获取服务池子信息
var hitCount=1;
var getUrlSub=function(desUrl,opID){
  $.get(encodeURI(desUrl+"&hitCount="+hitCount+"&randt="+new Date().getTime()),function(data,status){
    if(status=='success'){
      if(typeof data!== "undefined"){
        if(data!='gm'){
           $('#'+opID).html(data); 
        }else{
          //alert('cache');
        }
      }
    }
  });
};



var getRealTimeState=function(){
  getUrlSub("?r=dcmd-task-async/service-pool-state&task_id=<?php echo $task_id; ?>",'TaskAppPool');
  hitCount++;
};
getRealTimeState();
getRealTimeState();
var si_apppoolstatelist;
clearInterval(si_apppoolstatelist);
si_apppoolstatelist=setInterval(getRealTimeState,4000);

///获取各状态字任务信息
function getTaskTable(appPool){
  $('#TaskState').load(encodeURI("?r=dcmd-task-async/state-table&randt="+new Date().getTime()+"&task_id=<?php echo $task_id; ?>&service_pool="+appPool));
}
getTaskTable("all");
</script>
