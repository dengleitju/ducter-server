<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskNodeHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Task Node Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-node-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Task Node History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'subtask_id',
            'task_id',
            'task_cmd',
            'svr_pool',
            'svr_name',
            // 'ip',
            // 'state',
            // 'ignored',
            // 'start_time',
            // 'finish_time',
            // 'process',
            // 'err_msg',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
