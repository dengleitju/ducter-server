<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskNodeHistory */

$this->title = 'Create Dcmd Task Node History';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Node Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-node-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
