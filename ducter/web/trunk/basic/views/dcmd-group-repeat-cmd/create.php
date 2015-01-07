<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroupRepeatCmd */

$this->title = 'Create Dcmd Group Repeat Cmd';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Group Repeat Cmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-repeat-cmd-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
