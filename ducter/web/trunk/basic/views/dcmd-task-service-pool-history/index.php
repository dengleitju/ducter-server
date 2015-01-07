<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdTaskServicePoolHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Task Service Pool Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-service-pool-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Task Service Pool History', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'task_id',
            'task_cmd',
            'svr_pool',
            'svr_pool_id',
            // 'env_ver',
            // 'repo',
            // 'run_user',
            // 'undo_node',
            // 'doing_node',
            // 'finish_node',
            // 'fail_node',
            // 'ignored_fail_node',
            // 'ignored_doing_node',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
