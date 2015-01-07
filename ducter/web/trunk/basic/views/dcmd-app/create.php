<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */

$this->title = '添加产品';
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-app-create">

    <?= $this->render('_form', [
        'model' => $model,
        'depart' => $depart,
        'sys_user_group' => $sys_user_group,
        'svr_user_group' => $svr_user_group,
    ]) ?>

</div>
