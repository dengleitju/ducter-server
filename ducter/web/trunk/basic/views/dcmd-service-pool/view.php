<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = $model->svr_pool;
$this->params['breadcrumbs'][] = ['label' => '服务池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-view">

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
        <?= Html::a('更新', ['update', 'id' => $model->svr_pool_id], ['class' => 'btn btn-primary']) ?>
<!--        <?= Html::a('删除', ['delete', 'id' => $model->svr_pool_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           array('attribute'=>'svr_pool', 'label'=>'服务池子'),
           array('attribute'=>'repo', 'label'=>'版本地址'),
           array('attribute'=>'env_ver', 'label'=>'环境版本'),
           array('attribute'=>'comment', 'label'=>'说明'), 
        ],
    ]) ?>

    <p>
        <?= Html::a('添加', ['dcmd-service-pool-node/select-node-group', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id'], 'svr_pool_id'=>$model['svr_pool_id']], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'ip','label'=>'IP', 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model["ip"], Url::to(['dcmd-node/view', 'id'=>$model['id']]));},),
            array('attribute'=>'ip', 'label'=>'主机名', 'enableSorting'=>false, 'filter'=>false),
            array('attribute'=>'ip', 'label'=>'连接状态','enableSorting'=>false, 'filter'=>false),
            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service-pool-node/delete','id'=>$model['id'], 'svr_pool_id'=>$model['svr_pool_id']]);}],
        ],
    ]); ?>

</div>
