<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdRepeatExecSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Opr Cmd Repeat Execs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-repeat-exec-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Opr Cmd Repeat Exec', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'repeat_cmd_id',
            'repeat_cmd_name',
            'opr_cmd',
            'run_user',
            'timeout:datetime',
            // 'ip:ntext',
            // 'repeat',
            // 'cache_time:datetime',
            // 'ip_mutable',
            // 'arg_mutable',
            // 'arg:ntext',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
