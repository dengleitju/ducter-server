<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务池子列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'app_id','label'=>'产品','content' => function($model, $key, $index, $column) { return Html::a($model->getAppName($model['app_id']), Url::to(['dcmd-app/view', 'id'=>$model['app_id']]));}),
            array('attribute'=>'svr_id','label'=>'服务','content' => function($model, $key, $index, $column) { return Html::a($model->getServiceName($model['svr_id']), Url::to(['dcmd-service/view', 'id'=>$model['svr_id']]));}),
            array('attribute'=>'svr_pool','label'=>'服务池子','content' => function($model, $key, $index, $column) { return Html::a($model['svr_pool'], Url::to(['view', 'id'=>$model['svr_pool_id']]));}),
            array('attribute'=>'env_ver','label'=>'环境版本'),
        ],
    ]); ?>

</div>
