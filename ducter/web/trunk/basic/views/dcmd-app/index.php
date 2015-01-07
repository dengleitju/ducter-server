<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'app_name', 'label'=>'产品名称','content' => function($model, $key, $index, $column) { return Html::a($model['app_name'], Url::to(['view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'app_alias', 'label'=>'产品别名',  'enableSorting'=>false, 'content' => function($model, $key, $index, $column){ return Html::a($model['app_alias'], Url::to(['view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'sa_gid', 'label'=>'系统组', 'filter'=>$sys, 'content' => function($model, $key, $index, $column) { return Html::a($model->userGroupName($model['sa_gid']), Url::to(['dcmd-group/view', 'id'=>$model['sa_gid']]));}, 'enableSorting'=>false),
            array('attribute'=>'svr_gid', 'label'=>'业务组', 'filter'=>$svr, 'content' => function($model, $key, $index, $column) { return Html::a($model->userGroupName($model['svr_gid']), Url::to(['dcmd-group/view', 'id'=>$model['svr_gid']]));},'enableSorting'=>false),
            array('attribute'=>'depart_id','label'=>'部门', 'filter'=>$depart, 'value'=>function($model, $key, $index, $column) {return $model->department($model['depart_id']);},'enableSorting'=>false),

            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
        ],
    ]); ?>

</div>
