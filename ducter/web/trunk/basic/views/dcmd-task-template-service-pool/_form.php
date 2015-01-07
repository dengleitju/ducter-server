<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplateServicePool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-template-service-pool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'svr_pool_id')->dropDownList($service_pool)->label('服务池子') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
