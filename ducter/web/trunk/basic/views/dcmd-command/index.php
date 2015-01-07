<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdCommandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Commands';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-command-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Command', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cmd_id',
            'task_id',
            'subtask_id',
            'svr_pool',
            'svr_pool_id',
            // 'svr_name',
            // 'ip',
            // 'cmd_type',
            // 'state',
            // 'err_msg',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
