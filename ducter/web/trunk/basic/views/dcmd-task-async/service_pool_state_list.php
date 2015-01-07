<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="dcmd-task-service-pool-index">

<?php
$disabled = "";
if($task->freeze == 1) $disabled = "disabled";
?>
    <p>
       服务池子:  <?= Html::a('添加设备', Url::to(['dcmd-task-node/add', 'task_id'=>$task_id]), ['class' => 'btn btn-success', 'target'=>'_blank', $disabled=>true]) ?>
      &nbsp;&nbsp;
       <?=  Html::a('删除设备', ['dcmd-task-node/del', 'task_id'=>$task_id], ['class' => 'btn btn-success', 'target'=>'_blank', $disabled => true]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}",
        'columns' => [
            array('attribute'=>'svr_pool','label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false,'content' => function($model, $key, $inex, $column) { return Html::a($model['svr_pool'], Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));}),
            array('attribute'=>'id', 'label'=>'服务器总量', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { return Html::a($model['undo_node'] + $model['doing_node'] + $model['fail_node'] + $model['finish_node'] + $model['ignored_fail_node'] + $model['ignored_doing_node'], Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'] ]), [] );}), 
            array('attribute'=>'undo_node','label'=>'未执行', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['undo_node'],  Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>0]), ['target'=>''] );}),
            array('attribute'=>'doing_node','label'=>'在执行', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $indx,$col) { return Html::a($model['doing_node'],  Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>1]), ['target'=>''] );}),
            array('attribute'=>'ignored_doing_node', 'label'=>'执行中忽略', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $indx,$col) { return Html::a($model['ignored_doing_node'],  Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>1, 'ignored'=>1]), ['target'=>''] );}),
            array('attribute'=>'fail_node', 'label'=>'失败', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['fail_node'],  Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>3]), ['target'=>'']);}),
            array('attribute'=>'ignored_fail_node', 'label'=>'忽略失败', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['ignored_fail_node'],  Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>3, 'ignored'=>1]), ['target'=>'']);}),
            array('attribute'=>'finish_node', 'label'=>'成功', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index,$col) { return Html::a($model['finish_node'],  Url::to(['dcmd-task-node/index', 'task_id'=>$model['task_id'], 'svr_pool'=>$model['svr_pool'], 'state'=>2]), ['target'=>''] );}),
            array('attribute'=>'state', 'label'=>'状态', 'enableSorting'=>false, 'filter'=>false, 'content'=>function($model, $key, $index, $col) { if($model['state'] == 0) return "未提交"; if($model['state'] == 1) return "正在执行"; if($model['state'] == 2) return "失败"; if ($model['state']==3) return "完成"; if($model['state']==4) return "达到失败上限"; }),

        ],
    ]); ?>

</div>
