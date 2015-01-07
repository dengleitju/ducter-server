<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */

$this->title = 'Update Dcmd Opr Cmd: ' . ' ' . $model->opr_cmd_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->opr_cmd_id, 'url' => ['view', 'id' => $model->opr_cmd_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-opr-cmd-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
