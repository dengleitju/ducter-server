<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplate */

$this->title = $model->task_tmpt_name;
$this->params['breadcrumbs'][] = ['label' => '任务模板', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-template-view">
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
        <?= Html::a('修改', ['update', 'id' => $model->task_tmpt_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'task_tmpt_name', 'label'=>'模板名称'),
            array('attribute'=>'task_cmd', 'label'=>'任务脚本'),
            array('attribute'=>'app_id', 'label'=>'产品名称', 'value'=>$model->getAppName($model['app_id'])),
            array('attribute'=>'svr_name', 'label'=>'服务名称'),
            array('attribute'=>'update_env', 'label'=>'更新环境'),
            array('attribute'=>'concurrent_rate', 'label'=>'并发数'),
            array('attribute'=>'timeout','label'=>'超时'),
            array('attribute'=>'process', 'label'=>'输出进度'),
            array('attribute'=>'auto','label'=>'自动执行'),
            array('attribute'=>'comment', 'label'=>'说明'),
            'utime:text:修改时间',
            'ctime:text:创建时间',
        ],
    ]) ?>
    <div class="form-group field-dcmdtasktemplate-arg">
    <label class="task_arg" for="dcmdtasktemplate-arg">任务脚本参数</label>
    <div id="taskTypeArgDiv" style="width:100%"></div>
    <?php echo $arg_content; ?>
    </div>

    <p>
        <?= Html::a('添加', ['dcmd-task-template-service-pool/create', 'task_tmpt_id'=>$model['task_tmpt_id']], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            array('attribute'=>'svr_pool_id', 'label'=>'服务池子', 'content'=>function($model, $key, $index, $colum) { return Html::a($model->getServicePool($model['svr_pool_id']), Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));}, 'enableSorting'=>false, 'filter'=>false),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}', 'urlCreator'=>function($action, $model, $key, $index) {if ("delete" == $action) return Url::to(['dcmd-task-template-service-pool/delete', 'id'=>$model['id'], 'task_tmpt_id'=>$model['task_tmpt_id']]);}],
        ],
    ]); ?>
    <p align="center">
    <?= Html::a('创建任务', ['dcmd-task/create', 'task_tmpt_id' => $model->task_tmpt_id], ['class' => 'btn btn-primary' ]) ?>
    </p>
</div>
