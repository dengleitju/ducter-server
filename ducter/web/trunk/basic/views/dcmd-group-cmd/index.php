<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdGroupCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Group Cmds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-cmd-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Group Cmd', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'gid',
            'opr_cmd_id',
            'utime',
            'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
