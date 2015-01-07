<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = '更新产品: ' . ' ' . $model->app_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->app_name, 'url' => ['view', 'id' => $model->app_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="dcmd-app-update">

    <?= $this->render('_form', [
        'model' => $model,
        'depart' => $depart,
        'sys_user_group' => $sys_user_group,
        'svr_user_group' => $svr_user_group,
    ]) ?>

</div>
