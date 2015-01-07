<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmdRepeatExec */

$this->title = 'Update Dcmd Opr Cmd Repeat Exec: ' . ' ' . $model->repeat_cmd_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmd Repeat Execs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->repeat_cmd_id, 'url' => ['view', 'id' => $model->repeat_cmd_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-opr-cmd-repeat-exec-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
