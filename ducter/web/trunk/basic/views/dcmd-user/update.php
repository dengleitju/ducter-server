<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdUser */

$this->title = '修改用户: ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->uid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'depart' => $depart,
        'disabled' => 'disabled',
    ]) ?>

</div>
