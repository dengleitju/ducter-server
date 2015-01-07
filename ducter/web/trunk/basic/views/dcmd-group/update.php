<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */

$this->title = '修改用户组: ' . ' ' . $model->gname;
$this->params['breadcrumbs'][] = ['label' => '用户组', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gname, 'url' => ['view', 'id' => $model->gid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-group-update">

    <?= $this->render('_form', [
        'model' => $model,
        'disabled' => 'disabled',
    ]) ?>

</div>
