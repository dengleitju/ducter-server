<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */

$this->title = '修改服务: ' . ' ' . $model->svr_id;
$this->params['breadcrumbs'][] = ['label' => '服务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->svr_name, 'url' => ['view', 'id' => $model->svr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-service-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
