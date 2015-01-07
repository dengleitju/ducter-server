<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务模板列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-template-index">

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
            array('attribute'=>'task_tmpt_name', 'label'=>'任务模板名称', 'content'=>function($model, $key, $index,$column) { return  Html::a($model['task_tmpt_name'], Url::to(['view', 'id'=>$model['task_tmpt_id']]));},),
            array('attribute'=>'task_cmd_id', 'value'=>function($model, $key, $index, $col) { return $model['task_cmd'];}, 'label'=>'任务脚本', 'enableSorting'=>false, 'filter'=>$task_cmd),
            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>function($model, $key, $index, $colum) { return $model->getAppName($model['app_id']); }, 'enableSorting'=>false, 'filter'=>$app),
            array('attribute'=>'svr_id', 'value'=>function($model, $key, $index, $colum) { return $model['svr_name'];}, 'label'=>'服务名称', 'enableSorting'=>false, 'filter'=>$service),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
