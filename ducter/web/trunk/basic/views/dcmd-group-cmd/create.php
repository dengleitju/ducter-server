<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupCmd */

$this->title = 'Create Dcmd Group Cmd';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Group Cmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-cmd-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
