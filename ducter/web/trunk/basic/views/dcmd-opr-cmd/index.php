<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdOprCmdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Opr Cmds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Opr Cmd', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'opr_cmd_id',
            'opr_cmd',
            'ui_name',
            'run_user',
            'script_md5',
            // 'timeout:datetime',
            // 'comment',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
