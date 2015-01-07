<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dcmd-task-node-index">
<p><font color="#00BB00"><strong>
<?php echo Html::a('执行成功:',  Url::to(['dcmd-task-node/index','svr_pool'=>'', 'task_id'=>$task_id, 'state'=>2]), ['target'=>'','style'=>'color:#00BB00']); ?>
</strong></font></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'layout' => "{items}\n{pager}",
        'columns' => [
            array('attribute'=>'ip', 'label'=>'IP', 'enableSorting'=>false, 'filter'=>false,'content' => function($model, $key, $inex, $column) { return Html::a($model['ip'], Url::to(['agent-subtask-out', 'task_id'=>$model['task_id'], 'subtask_id'=>$model['subtask_id'],'task_cmd'=>$model['task_cmd'], 'ip'=>$model['ip']]), ['target'=>'_blank']);}),
            array('attribute'=>'svr_pool', 'label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false,'content' => function($model, $key, $inex, $column) { return Html::a($model['svr_pool'], Url::to(['dcmd-service-pool/view', 'id'=>$model->getSvrPoolId($model['svr_pool'])]), ['target'=>'_blank']);}),
            array('attribute'=>'ip', 'label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $col) { return $model->getAgentState($model['ip']);}),
            array('attribute'=>'process', 'label'=>'进度', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'start_time', 'label'=>'开始时间', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'finish_time', 'label'=>'耗时(s)', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $colum) { return strtotime($model['finish_time']) - strtotime($model['start_time']);}),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false,'content'=>function($model, $key, $index, $colum) { if ($model->getTaskFreeze($model['task_id'])) return "重做"; return " <a href=\"javascript:void(0);\" style=\"margin:0px 2px 0px 2px\" onclick=cmdSubmit(\"".Url::to(['redo-subtask', 'task_id'=>$model['task_id'], 'subtask_id'=>$model['subtask_id'], 'ip'=>$model['ip']])."\")>重做</a>";}),
        ],
    ]); ?>

</div>

