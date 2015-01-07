<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-user-index">

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
            array('attribute'=>'username', 'label'=>'用户名', 'content'=>function($model, $key, $index, $column) { return  Html::a($model['username'], Url::to(['view', 'id'=>$model['uid']]));},),
            array('attribute'=>'sa', 'label'=>'sa', 'enableSorting'=>false, 'filter'=>array(1=>"是", 0=>"否"),),
            array('attribute'=>'admin', 'label'=>'admin', 'enableSorting'=>false, 'filter'=>array(1=>"是", 0=>"否"),),
            array('attribute'=>'depart_id', 'label'=>'部门', 'filter'=>false,'enableSorting'=>false, 'value'=>function($model, $key, $index, $colum) {return $model->getDepartment($model['depart_id']);}),
            array('attribute'=>'tel', 'filter'=>false, 'enableSorting'=>false),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
