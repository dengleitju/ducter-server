<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdUserGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户组用户';
$this->params['breadcrumbs'][] = ['label' => $gname, 'url' => ['dcmd-group/view', 'id'=>$gid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-user-group-index">

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
        <?= Html::a('添加', ['create', 'gid'=>$gid, 'gname'=>$gname], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'uid', 'label'=>'用户名', 'filter'=>false, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getUsername($model['uid']), Url::to(['dcmd-user/view', 'id'=>$model['uid']]));}),
            array('attribute'=>'uid', 'label' => '部门', 'filter'=>false, 'enableSorting'=>false, 'value' => function($model, $key, $index, $column) { return $model->getDepartment($model['uid']);},),
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'urlCreator'=>function($action, $model, $key, $index){return Url::to(['dcmd-user-group/remove','gid'=>$model['gid'], 'id'=>$model['id']]);},],
        ],
    ]); ?>

</div>
