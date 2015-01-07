<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupRepeatCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-group-repeat-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gid')->textInput() ?>

    <?= $form->field($model, 'repeat_cmd_id')->textInput() ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
