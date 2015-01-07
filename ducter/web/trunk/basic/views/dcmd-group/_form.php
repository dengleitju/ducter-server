<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gname')->textInput(['maxlength' => 64, "$disabled"=>true])->label('用户组') ?>

    <?= $form->field($model, 'gtype')->dropDownList(array(1=>"系统组",2=>"业务组"))->label("组类型") ?>

    <?= $form->field($model, 'comment')->textarea(['maxlength' => 512])->label("说明") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
