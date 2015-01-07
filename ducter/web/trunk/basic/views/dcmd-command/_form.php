<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdCommand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-command-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

    <?= $form->field($model, 'subtask_id')->textInput() ?>

    <?= $form->field($model, 'svr_pool')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'svr_pool_id')->textInput() ?>

    <?= $form->field($model, 'svr_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'cmd_type')->textInput() ?>

    <?= $form->field($model, 'state')->textInput() ?>

    <?= $form->field($model, 'err_msg')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
