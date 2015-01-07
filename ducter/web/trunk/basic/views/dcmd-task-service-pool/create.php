<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskServicePool */

$this->title = 'Create Dcmd Task Service Pool';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Task Service Pools', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-service-pool-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
