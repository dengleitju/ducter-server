<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = '修改服务池子: ' . ' ' . $model->svr_pool;
$this->params['breadcrumbs'][] = ['label' => '服务池', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->svr_pool, 'url' => ['view', 'id' => $model->svr_pool_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-service-pool-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
