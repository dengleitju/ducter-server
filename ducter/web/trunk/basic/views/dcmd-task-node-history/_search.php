<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskNodeHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-node-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'subtask_id') ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'task_cmd') ?>

    <?= $form->field($model, 'svr_pool') ?>

    <?= $form->field($model, 'svr_name') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'ignored') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'finish_time') ?>

    <?php // echo $form->field($model, 'process') ?>

    <?php // echo $form->field($model, 'err_msg') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
