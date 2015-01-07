<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdDepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dcmd Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-department-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dcmd Department', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'depart_name:text:部门名称',
            'comment:text:分组描述',
            'utime:text:修改时间',
            'ctime:text:创建时间',
            // 'opr_uid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
