<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '更新服务器信息: ' . ' ' . $model->nid;
$this->params['breadcrumbs'][] = ['label' => '服务器列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ip, 'url' => ['view', 'id' => $model->nid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-node-update">


    <?= $this->render('_form', [
        'model' => $model,
        'node_group' => $node_group,
        'disabled' => "disabled",
    ]) ?>

</div>
