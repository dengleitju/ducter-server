<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmdArgSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-cmd-arg-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'task_cmd_id') ?>

    <?= $form->field($model, 'task_cmd') ?>

    <?= $form->field($model, 'arg_name') ?>

    <?= $form->field($model, 'optional') ?>

    <?php // echo $form->field($model, 'arg_type') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
