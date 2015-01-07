<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池子设备';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-node-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['select-node-group'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ip','label'=>'IP','enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model['ip'], Url::to(['dcmd-node/view', 'id'=>$model['nid']]));},),
            array('attribute'=>'app_id', 'label'=>'产品别名','filter'=>$app, 'enableSorting'=>false, 'content' => function($model, $key, $index, $column) { return Html::a($model->getAppAlias($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));},),
            array('attribute'=>'svr_id', 'label'=>'服务别名','enableSorting'=>false, 'filter'=>$svr, 'content' => function($model, $key, $index, $column) { return Html::a($model->getServiceAlias($model['svr_id']), Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));},),
            array('attribute'=>'svr_pool_id', 'label'=>'服务池子','enableSorting'=>false, 'filter'=>$svr_pool, 'content' => function($model, $key, $index, $column){
 return Html::a($model->getServicePoolName($model['svr_pool_id']), Url::to(['dcmd-service-pool/view', 'id'=>$model['svr_pool_id']]));},),
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
