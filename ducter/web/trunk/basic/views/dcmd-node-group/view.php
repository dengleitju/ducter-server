<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */

$this->title = "设备池子信息";
$this->params['breadcrumbs'][] = ['label' => '设备池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-view">

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

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->ngroup_id], ['class' => 'btn btn-primary']) ?>
        <!--<?= Html::a('删除', ['delete', 'id' => $model->ngroup_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute' => 'ngroup_name', 'label' => '设备池子'),
            array('attribute' => 'ctime', 'label' => '创建时间'),
            array('attribute' => 'utime', 'label' => '修改时间'),
            array('attribute' => 'gid', 'value'=>$model->getGname($model['gid']), 'label' =>
 '系统组' ),
            array('attribute' => 'comment', 'label' => '说明'),
        ],
    ]) ?>

    <p>
        <?= Html::a('添加', Url::to(['dcmd-node/create', 'ngroup_id'=>$ngroup_id]), ['class' => 'btn btn-success', "target"=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ip', 'label' => '服务器IP', 'content'=>function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));}),
            array('attribute'=>'host', 'label'=>'服务器名', 'filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'rack', 'label'=>'连接状态','filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'did', 'label'=>'设备序列号','filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'sid', 'label'=>'资产序列号', 'filter'=>false, 'enableSorting'=>false),
            array('attribute'=>'online_time', 'label'=>'上线时间', 'filter'=>false, 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" ==$action) { return Url::to(['dcmd-node/delete', 'id'=>$model['nid'], 'ngroup_id'=>$model['ngroup_id']]);}}],
        ],
    ]); ?>

</div>
