<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-department-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'depart_name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => 512]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
