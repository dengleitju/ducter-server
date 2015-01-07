<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskTemplateServicePoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务模板服务池子';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-template-service-pool-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            array('attribute'=>'svr_pool_id', 'label'=>'服务池子'),

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>

</div>
