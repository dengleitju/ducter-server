<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExecSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-opr-cmd-repeat-exec-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'repeat_cmd_id') ?>

    <?= $form->field($model, 'repeat_cmd_name') ?>

    <?= $form->field($model, 'opr_cmd') ?>

    <?= $form->field($model, 'run_user') ?>

    <?= $form->field($model, 'timeout') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'repeat') ?>

    <?php // echo $form->field($model, 'cache_time') ?>

    <?php // echo $form->field($model, 'ip_mutable') ?>

    <?php // echo $form->field($model, 'arg_mutable') ?>

    <?php // echo $form->field($model, 'arg') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
