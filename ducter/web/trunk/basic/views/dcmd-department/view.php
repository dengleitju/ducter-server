<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */

$this->title = "部门详细信息";
$this->params['breadcrumbs'][] = ['label' => '部门', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-department-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->depart_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->depart_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
             'attribute'=>'depart_id',
             'format'=>'raw',
             'label' => '部门ID',
             'value'=> 'depart_id'
            ],
            [
             'attribute' => 'depart_name',
             'format' => 'raw',
             'label' => '部门名称',
            ],
            'comment:text:描述',
            'utime:text:更新时间',
            'ctime:text:创建时间',
            'opr_uid:text:操作者',
        ],
    ]) ?>

</div>
