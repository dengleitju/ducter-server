<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ui_name')->textInput(['maxlength' => 64])->label('任务名称') ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64, 'onblur' => "javascript:getTaskScriptContent()"])->label('脚本名称') ?>
    <p>
<button type="button"  onclick="javascript:getTaskScriptContent()" class="btn btn-success">加载</button>  
    </p> 
    <?= $form->field($model, 'script_md5')->textInput(['maxlength' => 32, 'readonly'=>true])->label('脚本MD5') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时时间') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div style="height: auto; width: 800px; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px">
 任务脚本内容:
 <div id="ShellContent" style="margin: 10px 0px 10px 10px; overflow-y: auto; height: auto; overflow-x: hidden">
  <div style=""></div>
 </div>
</div>
<script>
var getTaskScriptContent = function () {
	 task_cmd=$('#dcmdtaskcmd-task_cmd').val();
	 $.post("?r=dcmd-task-cmd/load-content", { "task_cmd":task_cmd }, function (data, status) {
				status == "success" ? function () {
					var dataO = jQuery.parseJSON(data); 
					$('#ShellContent').html(dataO.result);
					$('#dcmdtaskcmd-script_md5').val(dataO.md5);		
				} () : "";
			}, "text");
};
</script>
