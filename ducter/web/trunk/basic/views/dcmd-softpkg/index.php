<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdSoftpkgSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Softpkgs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-softpkg-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Softpkg', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'svr_id',
            'version',
            'repo_file',
            // 'upload_file',
            // 'utime',
            // 'ctime',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
