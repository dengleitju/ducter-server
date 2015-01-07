<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-opr-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'log_table')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'opr_type')->textInput() ?>

    <?= $form->field($model, 'sql_statement')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
