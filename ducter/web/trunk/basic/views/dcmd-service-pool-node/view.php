<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolNode */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Service Pool Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-node-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'svr_pool_id',
            'svr_id',
            'nid',
            'app_id',
            'ip',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
