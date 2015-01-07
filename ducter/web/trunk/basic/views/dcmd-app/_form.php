<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdApp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-app-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => 128])->label('产品名称') ?>

    <?= $form->field($model, 'app_alias')->textInput(['maxlength' => 128])->label('产品别名') ?>

    <?= $form->field($model, 'sa_gid')->dropDownList($sys_user_group)->label("系统组") ?>

    <?= $form->field($model, 'svr_gid')->dropDownList($svr_user_group)->label("业务组") ?>

    <?= $form->field($model, 'depart_id')->dropDownList($depart)->label('部门')?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
