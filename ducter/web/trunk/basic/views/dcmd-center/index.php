<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdCenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '控制中心';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-center-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'host', 'label'=>'控制中心IP',  'filter'=>false, 'enableSorting'=>false, ),
            array('attribute'=>'master', 'label'=>'是否是master',  'filter'=>false, 'enableSorting'=>false, 'value'=>function($model, $key, $index, $column) { if($model['master'] == '1') return "是"; else  return "否";}),
            array('attribute'=>'update_time', 'label'=>'心跳时间',  'filter'=>false, 'enableSorting'=>false, ),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
