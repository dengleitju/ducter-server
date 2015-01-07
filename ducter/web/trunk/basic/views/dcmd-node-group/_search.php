<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-group-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ngroup_id') ?>

    <?= $form->field($model, 'ngroup_name') ?>

    <?= $form->field($model, 'gid') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
