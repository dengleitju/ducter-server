<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-node-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nid') ?>

    <?= $form->field($model, 'ip') ?>

    <?= $form->field($model, 'ngroup_id') ?>

    <?= $form->field($model, 'host') ?>

    <?= $form->field($model, 'sid') ?>

    <?php // echo $form->field($model, 'did') ?>

    <?php // echo $form->field($model, 'os_type') ?>

    <?php // echo $form->field($model, 'os_ver') ?>

    <?php // echo $form->field($model, 'bend_ip') ?>

    <?php // echo $form->field($model, 'public_ip') ?>

    <?php // echo $form->field($model, 'mach_room') ?>

    <?php // echo $form->field($model, 'rack') ?>

    <?php // echo $form->field($model, 'seat') ?>

    <?php // echo $form->field($model, 'online_time') ?>

    <?php // echo $form->field($model, 'server_brand') ?>

    <?php // echo $form->field($model, 'server_model') ?>

    <?php // echo $form->field($model, 'cpu') ?>

    <?php // echo $form->field($model, 'memory') ?>

    <?php // echo $form->field($model, 'disk') ?>

    <?php // echo $form->field($model, 'purchase_time') ?>

    <?php // echo $form->field($model, 'maintain_time') ?>

    <?php // echo $form->field($model, 'maintain_fac') ?>

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
