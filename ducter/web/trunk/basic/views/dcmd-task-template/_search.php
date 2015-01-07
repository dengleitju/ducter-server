<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'task_tmpt_id') ?>

    <?= $form->field($model, 'task_tmpt_name') ?>

    <?= $form->field($model, 'task_cmd_id') ?>

    <?= $form->field($model, 'task_cmd') ?>

    <?= $form->field($model, 'svr_id') ?>

    <?php // echo $form->field($model, 'svr_name') ?>

    <?php // echo $form->field($model, 'app_id') ?>

    <?php // echo $form->field($model, 'update_env') ?>

    <?php // echo $form->field($model, 'concurrent_rate') ?>

    <?php // echo $form->field($model, 'timeout') ?>

    <?php // echo $form->field($model, 'process') ?>

    <?php // echo $form->field($model, 'auto') ?>

    <?php // echo $form->field($model, 'task_arg') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
