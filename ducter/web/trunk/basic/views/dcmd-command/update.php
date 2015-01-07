<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdCommand */

$this->title = 'Update Dcmd Command: ' . ' ' . $model->cmd_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Commands', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cmd_id, 'url' => ['view', 'id' => $model->cmd_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dcmd-command-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
