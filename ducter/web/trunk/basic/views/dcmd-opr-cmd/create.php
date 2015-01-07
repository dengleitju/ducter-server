<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */

$this->title = 'Create Dcmd Opr Cmd';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
