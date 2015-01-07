<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_tmpt_name')->textInput(['maxlength' => 128])->label('任务模板名称') ?>

    <?= $form->field($model, 'app_id')->dropDownList($app, ['onchange'=>'javascript:getService()'])->label('产品名称') ?>

    <?= $form->field($model, 'svr_id')->dropDownList($svr)->label('服务名称') ?>
  
    <?= $form->field($model, 'task_cmd_id')->dropDownList($task_cmd, ["onchange"=>"javascript:taskTypeSelect()"])->label('任务脚本') ?>
    
    <?= $form->field($model, 'update_env')->dropDownList(array(0=>"不更新", 1=>"更新"))->label('更新环境') ?>

    <?= $form->field($model, 'process')->dropDownList(array(0=>"不输出", 1=>"输出"))->label('输出进度') ?>

    <?= $form->field($model, 'concurrent_rate')->textInput()->label('并发数') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时时间') ?>

    <?= $form->field($model, 'auto')->dropDownList(array(0=>"否", 1=>"是"))->label('自动执行') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512, 'rows'=>4])->label('说明') ?>
  
    <div class="form-group field-dcmdtasktemplate-arg">
    <label class="task_arg" for="dcmdtasktemplate-arg">任务脚本参数</label>
    <div id="taskTypeArgDiv" style="width:100%"></div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
var getService = function () {
 var service = document.getElementById("dcmdtasktemplate-svr_id");
 service.options.length = 0;
 var app = document.getElementById("dcmdtasktemplate-app_id").value;
 if(app == "") return 0;
 $.post("?r=dcmd-task-template/get-services", {"app_id":app}, function(data, status) {
 if (data != "") {
  var dataO = data.split(";");
  service.options.add(new Option("",""));
  for(i=0;i<dataO.length;i++) {
   if (dataO[i] == "")
    continue;
    var d = dataO[i].split(",")
   service.options.add(new Option(d[1], d[0]));
 }
}
}, "text"); 
}

var taskTypeSelect = function(){
  tasktype=$('#dcmdtasktemplate-task_cmd_id').val();
  $('#taskTypeArgDiv').load("?r=dcmd-task-template/get-task-type-arg&task_cmd_id="+tasktype);
}


</script>
