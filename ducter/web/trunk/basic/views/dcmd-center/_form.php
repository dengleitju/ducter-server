<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdCenter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-center-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'master')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
