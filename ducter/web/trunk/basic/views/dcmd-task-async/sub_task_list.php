<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="dcmd-task-node-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterRowOptions' => array('style'=>'display:none'),
        'headerRowOptions' => array('bgcolor'=>'#E6E6FA'),
        'columns' => [
            array('attribute'=>'ip', 'label'=>'IP', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'svr_pool', 'label'=>'服务池子', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ignored', 'label'=>'ignore', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'process', 'label'=>'进度', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'start_time', 'label'=>'开始时间', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'finish_time', 'label'=>'耗时(s)', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'操作', 'enableSorting'=>false, 'filter'=>false),
        ],
    ]); ?>

</div>

