<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePoolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dcmd-task-service-pool-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'task_cmd') ?>

    <?= $form->field($model, 'svr_pool') ?>

    <?= $form->field($model, 'svr_pool_id') ?>

    <?php // echo $form->field($model, 'env_ver') ?>

    <?php // echo $form->field($model, 'repo') ?>

    <?php // echo $form->field($model, 'run_user') ?>

    <?php // echo $form->field($model, 'undo_node') ?>

    <?php // echo $form->field($model, 'doing_node') ?>

    <?php // echo $form->field($model, 'finish_node') ?>

    <?php // echo $form->field($model, 'fail_node') ?>

    <?php // echo $form->field($model, 'ignored_fail_node') ?>

    <?php // echo $form->field($model, 'ignored_doing_node') ?>

    <?php // echo $form->field($model, 'utime') ?>

    <?php // echo $form->field($model, 'ctime') ?>

    <?php // echo $form->field($model, 'opr_uid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
