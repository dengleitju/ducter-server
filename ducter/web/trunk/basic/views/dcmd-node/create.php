<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = '添加设备';
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-create">


    <?= $this->render('_form', [
        'model' => $model,
        'node_group' => $node_group,
        'disabled' => "enable",
    ]) ?>

</div>
