<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdAppSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-app-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'app_id') ?>

    <?= $form->field($model, 'app_name') ?>

    <?= $form->field($model, 'app_alias') ?>

    <?= $form->field($model, 'sa_gid') ?>

    <?= $form->field($model, 'svr_gid') ?>

    <?php // echo $form->field($model, 'depart_id') ?>

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
