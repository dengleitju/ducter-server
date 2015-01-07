<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'svr_pool')->textInput(['maxlength' => 128])->label('服务池子名称') ?>

    <?= $form->field($model, 'svr_id')->textInput(["style"=>"display:none"])->label('服务',["style"=>"display:none"]) ?>

    <?= $form->field($model, 'app_id')->textInput(["style"=>"display:none"])->label('产品',["style"=>"display:none"]) ?>

    <?= $form->field($model, 'repo')->textInput(['maxlength' => 512])->label('版本库地址') ?>

    <?= $form->field($model, 'env_ver')->textInput(['maxlength' => 64])->label('环境版本') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
