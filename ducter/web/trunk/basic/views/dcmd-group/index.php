<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户组';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-index">

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
            array('attribute'=>'gname', 'label'=>'用户组', 'content'=>function($model, $key, $index, $column) { return  Html::a($model['gname'], Url::to(['view', 'id'=>$model['gid']]));},),
            array('attribute'=>'gtype', 'label'=>'组类型', 'filter'=>array(1=>"系统组", 2=>"业务组"), 'value'=>function($model, $key, $index, $column) { if($model['gtype'] == 1) return "系统组"; else return "业务组";}),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
