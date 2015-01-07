<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $task->task_name;
$this->params['breadcrumbs'][] = ['label' => '历史任务', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!---任务基本信息 --->
<div class="dcmd-app-index">
<table class="table table-striped table-bordered">
<tbody><tr><td width=15%>操作:</td><td>
 

<?= Html::a('启动', ['start-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定启动任务?',
                'method' => 'post',
            ],
            "disabled" => true,
 ]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
<?= Html::a('暂停', ['pause-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定暂停?',
                'method' => 'post',
            ],
            "disabled" => true,
]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
<?= Html::a('继续', ['resume-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定继续?',
                'method' => 'post',
            ],
            "disabled" => true,
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
            "disabled" =>  true,
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
            "disabled" => true,
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
            "disabled" => true,
 ]) ?>
&nbsp;
&nbsp;
&nbsp;
&nbsp;
 <?= Html::a('解冻', ['unfreeze-task', 'task_id' => $task->task_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定解冻',
                'method' => 'post',
            ],
            "disabled" => true,
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
<?= Html::submitButton('更新', ['class' => 'btn btn-primary', 'align'=>'right', "disabled"=>true])?>
</td>
</tr></tbody></table>
</form>

<!---任务服务池子信息-->
<div id="TaskAppPool">
<div class="dcmd-task-service-pool-index">
 <p>
 服务池子:  <?= Html::a('添加设备', Url::to(['#', 'task_id'=>$task->task_id]), ['class' => 'btn btn-success', 'target'=>'_blan
k', "disabeld"=>true]) ?>
  &nbsp;&nbsp;
  <?=  Html::a('删除设备', ['#', 'task_id'=>$task->task_id], ['class' => 'btn btn-success', 'target'=>'_blank', "disabled" => true]) ?>
 </p>
    <?= GridView::widget([
        'dataProvider' => $svr_dataProvider,
        'filterModel' => $svr_searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}",
        'columns' => [
            array('attribute'=>'svr_pool','label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false,'content' => function($model, $key, $inex, $column) { return Html::a($model['svr_pool'], Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));}),
            array('attribute'=>'id', 'label'=>'服务器总量', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['undo_node'] + $model['doing_node'] + $model['fail_node'] + $model['finish_node'] + $model['ignored_fail_node'] + $model['ignored_doing_node'], Url::to(['#', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'] ]), [] );}),
            array('attribute'=>'undo_node','label'=>'未执行', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['undo_node'],  Url::to(['#', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>0]), ['target'=>''] );}),
            array('attribute'=>'doing_node','label'=>'在执行', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $ind,$col) { return Html::a($model['doing_node'],  Url::to(['#', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>1]), ['target'=>''] );}),
            array('attribute'=>'fail_node', 'label'=>'失败', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['fail_node'],  Url::to(['#', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>3]), ['target'=>'']);}),
            array('attribute'=>'finish_node', 'label'=>'成功', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['finish_node'],  Url::to(['#', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>2]), ['target'=>''] );}),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { if($model['state'] == 0) return "未提交"; if($model['state'] == 1) return "正在执行"; if($model['state'] == 2) return "失败"; if ($model['state']==3) return "完成"; if($model['state']==4) return "达到失败上限"; }),
        ],
    ]); ?>
 </div>
</div>

<!---任务执行状况信息--->
<div id="TaskState">
<!---未执行--->
<div class="dcmd-task-node-index">

<p><font color="#000000"><strong>
<?php echo Html::a('未执行:',  Url::to(['#','svr_pool'=>'', 'task_id'=>$task->task_id, 'state'=>0]), ['target'=>'','style'=>'color:#000000'] ); ?>
</strong></font></p>
    <?= GridView::widget([
        'dataProvider' => $init_dataProvider,
        'filterModel' => $init_searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'ip', 'label'=>'IP', 'enableSorting'=>false, 'filter'=>false, ),
            array('attribute'=>'svr_pool', 'label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $colum) {return "执行";}),
        ],
    ]); ?>

</div>

<!--正在执行的结果-->
<p><font color="#0000FF"><strong>
<?php echo Html::a('正在执行:',  Url::to(['#','svr_pool'=>'', 'task_id'=>$task->task_id, 'state'=>1]), ['target'=>'', 'style'=>'color:#0000FF'] ); ?>
</strong></font></p>
    <?= GridView::widget([
        'dataProvider' => $run_dataProvider,
        'filterModel' => $run_searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'ip', 'label'=>'IP', 'enableSorting'=>false, 'filter'=>false,),
            array('attribute'=>'svr_pool', 'label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $col) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'ignored', 'label'=>'ignore', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'process', 'label'=>'进度', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'start_time', 'label'=>'开始时间', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'finish_time', 'label'=>'耗时(s)', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $colum) { return strtotime($model['finish_time']) - strtotime($model['start_time']);}),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $colum) {return "终止  忽略";}),
        ],
    ]); ?>

</div>
<!---执行失败--->
<div class="dcmd-task-node-index" >
<p><font color="#FF0000"><strong>
<?php echo Html::a('执行失败:',  Url::to(['#','svr_pool'=>'', 'task_id'=>$task->task_id, 'state'=>3]), ['target'=>'', 'style'=>'color:#FF0000'] ); ?>
</strong></font></p>
    <?= GridView::widget([
        'dataProvider' => $fail_dataProvider,
        'filterModel' => $fail_searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'ip', 'label'=>'IP', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'svr_pool', 'label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'ignored', 'label'=>'ignore', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'process', 'label'=>'进度', 'enableSorting'=>false, 'filter'=>false,),
            array('attribute'=>'start_time', 'label'=>'开始时间', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'finish_time', 'label'=>'耗时(s)', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $colum) { return strtotime($model['finish_time']) - strtotime($model['start_time']);}),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $colum) {  return "重做 忽略"; }),
        ],
    ]); ?>

</div>

<!--执行完成--->
<div class="dcmd-task-node-index">
<p><font color="#00BB00"><strong>
<?php echo Html::a('执行成功:',  Url::to(['#','svr_pool'=>'', 'task_id'=>$task->task_id, 'state'=>2]), ['target'=>'','style'=>'color:#00BB00']); ?>
</strong></font></p>
    <?= GridView::widget([
        'dataProvider' => $suc_dataProvider,
        'filterModel' => $suc_searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'ip', 'label'=>'IP', 'enableSorting'=>false, 'filter'=>false,),
            array('attribute'=>'svr_pool', 'label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false,),
            array('attribute'=>'ip', 'label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $col) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'process', 'label'=>'进度', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'start_time', 'label'=>'开始时间', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'finish_time', 'label'=>'耗时(s)', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $colum) { return strtotime($model['finish_time']) - strtotime($model['start_time']);}),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $colum) { return "重做"; }),
        ],
    ]); ?>

</div>

</div>

</div>


