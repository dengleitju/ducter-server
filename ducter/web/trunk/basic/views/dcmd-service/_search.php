<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdServiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-service-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'svr_id') ?>

    <?= $form->field($model, 'svr_name') ?>

    <?= $form->field($model, 'svr_alias') ?>

    <?= $form->field($model, 'svr_path') ?>

    <?= $form->field($model, 'run_user') ?>

    <?php // echo $form->field($model, 'app_id') ?>

    <?php // echo $form->field($model, 'owner') ?>

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
