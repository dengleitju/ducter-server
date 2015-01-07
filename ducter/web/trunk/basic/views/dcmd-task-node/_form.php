<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'svr_pool')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'svr_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'state')->textInput() ?>

    <?= $form->field($model, 'ignored')->textInput() ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'finish_time')->textInput() ?>

    <?= $form->field($model, 'process')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'err_msg')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
