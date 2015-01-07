<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ngroup_name')->textInput(['maxlength' => 128, ])->label("设备组名")?>

    <?= $form->field($model, 'gid')->dropDownList($groups)->label("系统组") ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label("说明") ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
