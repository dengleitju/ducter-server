<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = $model->app_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-view">
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
        <?= Html::a('更新', ['update', 'id' => $model->app_id], ['class' => 'btn btn-primary']) ?>
     <!--   <?= Html::a('删除', ['delete', 'id' => $model->app_id], [
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
            array('attribute'=>'app_name', 'label'=>'产品名称'),
            array('attribute'=>'app_alias', 'label'=>'产品别名'),
            array('attribute'=>'sa_gid','label'=>'系统组', 'value'=>$model->userGroupName($model['sa_gid'])),
            array('attribute'=>'svr_gid', 'label'=>'业务组', 'value'=>$model->userGroupName($model['svr_gid'])),
            array('attribute'=>'depart_id', 'label'=>'部门', 'value'=>$model->department($model['depart_id'])),
            array('attribute'=>'comment', 'label'=>'说明'),
        ],
    ]) ?>
    <p> 
       <?= Html::a('添加', ['dcmd-service/create', 'app_id' => $model->app_id], ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_name', 'label'=>'服务名称', 'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_name'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_alias', 'label'=>'服务别名', 'content'=>function($model, $key, $index, $column) { return Html::a($model['svr_alias'], Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service/delete', 'id'=>$model['svr_id'], 'app_id'=>$model['app_id']]);}],
        ],
    ]); ?>



</div>
