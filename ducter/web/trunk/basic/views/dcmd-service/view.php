<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */

$this->title = $model->svr_name;
$this->params['breadcrumbs'][] = ['label' => '服务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-view">
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
        <?= Html::a('更新', ['update', 'id' => $model->svr_id], ['class' => 'btn btn-primary']) ?>
    <!--    <?= Html::a('删除', ['delete', 'id' => $model->svr_id], [
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
            array('attribute'=>'svr_name', 'label'=>'服务名字'),
            array('attribute'=>'svr_alias', 'label'=>'服务别名'),
            array('attribute'=>'svr_path', 'label'=>'安装路径'),
            array('attribute'=>'run_user', 'label'=>'运行用户'),
            array('attribute'=>'app_id', 'label'=>'所属产品', 'value'=>$model->getAppName($model['app_id'])),
            array('attribute'=>'owner', 'label'=>'拥有者'),
            array('attribute'=>'comment', 'label'=>'说明'),
            array('attribute'=>'utime', 'label'=>'修改时间'),
            array('attribute'=>'ctime', 'label'=>'创建时间'),
            array('attribute'=>'opr_uid', 'label'=>'修改者'),
        ],
    ]) ?>

    <p>
        <?= Html::a('添加', ['dcmd-service-pool/create', 'app_id'=>$model['app_id'], 'svr_id'=>$model['svr_id']], ['class' => 'btn btn-success', 'target'=>"_blank"]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_pool','label'=>'服务池子','content' => function($model, $key, $index, $column) { return Html::a($model['svr_pool'], Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));}),
            array('attribute'=>'env_ver', 'label'=>'环境版本', 'filter'=>false, 'enableSorting'=>false,),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}','urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-service-pool/delete', 'id'=>$model['svr_pool_id'], 'svr_id'=>$model['svr_id']]);}],
        ],
    ]); ?>

</div>
