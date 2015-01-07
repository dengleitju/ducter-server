<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 16, "$disabled"=>"true"])->label('服务器IP') ?>

    <?= $form->field($model, 'ngroup_id')->dropDownList($node_group)->label('设备池子') ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => 128])->label('主机名') ?>

    <?= $form->field($model, 'sid')->textInput(['maxlength' => 128])->label('资产序列号') ?>

    <?= $form->field($model, 'did')->textInput(['maxlength' => 128])->label('设备序列号') ?>

    <?= $form->field($model, 'os_type')->textInput(['maxlength' => 128])->label('操作系统类型') ?>

    <?= $form->field($model, 'os_ver')->textInput(['maxlength' => 128])->label('操作系统版本号') ?>

    <?= $form->field($model, 'bend_ip')->textInput(['maxlength' => 16])->label('带外IP') ?>

    <?= $form->field($model, 'public_ip')->textInput(['maxlength' => 16])->label('公网IP') ?>

    <?= $form->field($model, 'mach_room')->textInput(['maxlength' => 128])->label('机房') ?>

    <?= $form->field($model, 'rack')->textInput(['maxlength' => 32])->label('机架') ?>

    <?= $form->field($model, 'seat')->textInput(['maxlength' => 32])->label('机位') ?>

    <?= $form->field($model, 'online_time')->textInput()->label('上线时间') ?>

    <?= $form->field($model, 'server_brand')->textInput(['maxlength' => 128])->label('服务器品牌') ?>

    <?= $form->field($model, 'server_model')->textInput(['maxlength' => 32])->label('服务器型号') ?>

    <?= $form->field($model, 'cpu')->textInput(['maxlength' => 32])->label('CPU信息') ?>

    <?= $form->field($model, 'memory')->textInput(['maxlength' => 32])->label('内存信息') ?>

    <?= $form->field($model, 'disk')->textInput(['maxlength' => 64])->label('磁盘信息') ?>

    <?= $form->field($model, 'purchase_time')->textInput()->label('采购时间') ?>

    <?= $form->field($model, 'maintain_time')->textInput()->label('维保时间') ?>

    <?= $form->field($model, 'maintain_fac')->textInput(['maxlength' => 128])->label('维保厂家') ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label('说明') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
