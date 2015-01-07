<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'passwd') ?>

    <?= $form->field($model, 'sa') ?>

    <?= $form->field($model, 'admin') ?>

    <?php // echo $form->field($model, 'depart_id') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
