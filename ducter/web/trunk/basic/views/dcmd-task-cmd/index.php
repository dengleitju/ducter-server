<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务脚本管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-cmd-index">

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

            array('attribute'=>'task_cmd', 'label'=>"脚本名称", 'content'=>function($model, $key, $index, $column){ return Html::a($model['task_cmd'], Url::to(['view', 'id'=>$model['task_cmd_id']]));}),
            array('attribute'=>'script_md5', 'label'=>"MD5",'enableSorting'=>false, 'filter'=>false),
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
        ],
    ]); ?>

</div>
