<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */

$this->title = 'Update Dcmd Department: ' . ' ' . $model->depart_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->depart_id, 'url' => ['view', 'id' => $model->depart_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-department-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
