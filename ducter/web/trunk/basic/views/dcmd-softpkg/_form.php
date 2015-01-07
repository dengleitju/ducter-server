<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdSoftpkg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-softpkg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'svr_id')->textInput() ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'repo_file')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'upload_file')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
