<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */

$this->title = '修改设备信息';
$this->params['breadcrumbs'][] = ['label' => '设备池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ngroup_name, 'url' => ['view', 'id' => $model->ngroup_id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="dcmd-node-group-update">


    <?= $this->render('_form', [
        'model' => $model,
        'groups' => $groups,
    ]) ?>

</div>
