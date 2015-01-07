<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupCmdSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-group-cmd-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gid') ?>

    <?= $form->field($model, 'opr_cmd_id') ?>

    <?= $form->field($model, 'utime') ?>

    <?= $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
