<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTask */

$this->title = 'Update Dcmd Task: ' . ' ' . $model->task_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->task_id, 'url' => ['view', 'id' => $model->task_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
