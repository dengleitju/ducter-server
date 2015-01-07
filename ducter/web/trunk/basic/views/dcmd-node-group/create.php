<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */

$this->title = '添加设备池子';
$this->params['breadcrumbs'][] = ['label' => '设备池子', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-group-create">


    <?= $this->render('_form', [
        'model' => $model,
        'groups' => $groups,
    ]) ?>

</div>
