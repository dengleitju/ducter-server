<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-cmd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 64, 'readonly'=>true])->label('任务模板名称') ?>

    <div class="form-group field-dcmdtask-svr_id required">
    <label class="control-label" for="dcmdtask-app">产品名称</label>
    <input type="text" id="app" class="form-control" name="app" value="<?php echo $app; ?>" readonly maxlength="32">
    </div>

    <?= $form->field($model, 'svr_id')->textInput(['maxlength' => 32, 'style'=>'display:none'])->label('svr_id', ['style'=>'display:none',]) ?>

    <?= $form->field($model, 'svr_name')->textInput(['maxlength' => 32, 'readonly'=>true])->label('应用名称') ?>

    <?= $form->field($model, 'task_cmd')->textInput(['maxlength' => 32, 'readonly'=>true])->label('任务类型') ?>

    <div class="form-group field-dcmdtask-svr_id required">
    <label class="control-label" for="dcmdtask-task_cmd_prv">任务名称</label>
    <input type="text" id="task_cmd_prv" class="form-control" name="task_cmd_prv" value="<?php echo $task_cmd_prv; ?>" readonly maxlength="32">
    </div>

    <?= $form->field($model, 'task_name')->textInput(['maxlength' => 32, 'value'=>''])->label('任务后缀名') ?>

    <?= $form->field($model, 'timeout')->textInput()->label('超时时间') ?>

    <?= $form->field($model, 'process')->dropDownList(array(0=>"否", 1=>'是'))->label('输出进度') ?>

    <?= $form->field($model, 'update_env')->dropDownList(array(0=>'否', 1=>'是'))->label('更新环境') ?>

    <?= $form->field($model, 'concurrent_rate')->textInput(['maxlength' => 32])->label('并发数') ?>

    <?= $form->field($model, 'tag')->textInput(['maxlength' => 128])->label('上线版本') ?>

    <?= $form->field($model, 'update_tag')->dropDownList(array(0=>"否", 1=>'是'))->label('更新版本') ?>
 
    <?= $form->field($model, 'auto')->dropDownList(array(0=>"否", 1=>'是'))->label('自动执行') ?>

    <?= $form->field($model, 'svr_path')->textInput(['maxlength' => 256])->label('上线路径') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>

    <?php echo $args; ?>
   
    服务池子:
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'svr_pool_id','label'=>'服务池子', 'value'=>function($model, $key, $index, $column) { return $model['svr_pool'];
},),
            array('attribute'=>'env_ver', 'label'=>'池子配置版本'),
        ],
    ]); ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('下一步' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
function checkAll() 
{ 
  var code_Values = document.getElementsByTagName("input"); 
  for(i = 0;i < code_Values.length;i++){ 
    if(code_Values[i].type == "checkbox") 
    { 
     code_Values[i].checked = true; 
    } 
  }   
}
checkAll(); 
</script>
