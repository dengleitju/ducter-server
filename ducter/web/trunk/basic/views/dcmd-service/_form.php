<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'svr_name')->textInput(['maxlength' => 128])->label('服务名称') ?>

    <?= $form->field($model, 'svr_alias')->textInput(['maxlength' => 128])->label('服务别名') ?>

    <?= $form->field($model, 'svr_path')->textInput(['maxlength' => 128])->label('安装路径') ?>

    <?= $form->field($model, 'run_user')->textInput(['maxlength' => 16])->label('运行用户') ?>
    
   <?= $form->field($model, 'app_id')->textInput(["style"=>"display:none"])->label('产品' ,["style"=>"display:none"]) ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
