<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-service-pool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'svr_pool')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'svr_pool_id')->textInput() ?>

    <?= $form->field($model, 'env_ver')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'repo')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'run_user')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'undo_node')->textInput() ?>

    <?= $form->field($model, 'doing_node')->textInput() ?>

    <?= $form->field($model, 'finish_node')->textInput() ?>

    <?= $form->field($model, 'fail_node')->textInput() ?>

    <?= $form->field($model, 'ignored_fail_node')->textInput() ?>

    <?= $form->field($model, 'ignored_doing_node')->textInput() ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
