<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmdArg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-cmd-arg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_cmd_id')->textInput(["style"=>"display:none",])->label("脚本ID", ["style"=>"display:none"]) ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64,"style"=>"display:none"])->label("任务脚本名称", ["style"=>"display:none"]) ?>

    <?= $form->field($model, 'arg_name')->textInput(['maxlength' => 32])->label('参数名') ?>

    <?= $form->field($model, 'optional')->dropDownList(array(1=>"是", 0=>"否"))->label('是否可选') ?>

    <?= $form->field($model, 'arg_type')->dropDownList(array(1=>"int", 2=>"float", 3=>"bool", 4=>"char", 5=>"datetime"))->label('参数类型') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
