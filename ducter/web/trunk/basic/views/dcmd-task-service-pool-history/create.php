<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePoolHistory */

$this->title = 'Create Dcmd Task Service Pool History';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Service Pool Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-service-pool-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
