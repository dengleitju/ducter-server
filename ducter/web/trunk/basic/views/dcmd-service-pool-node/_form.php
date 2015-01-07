<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolNode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-pool-node-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'svr_pool_id')->textInput() ?>

    <?= $form->field($model, 'svr_id')->textInput() ?>

    <?= $form->field($model, 'nid')->textInput() ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'utime')->textInput() ?>

    <?= $form->field($model, 'ctime')->textInput() ?>

    <?= $form->field($model, 'opr_uid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
