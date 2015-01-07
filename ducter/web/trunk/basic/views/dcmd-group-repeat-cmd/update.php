<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupRepeatCmd */

$this->title = 'Update Dcmd Group Repeat Cmd: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Group Repeat Cmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-group-repeat-cmd-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
