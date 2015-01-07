<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-opr-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'opr_cmd')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'ui_name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'run_user')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'script_md5')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'timeout')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => 512]) ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
