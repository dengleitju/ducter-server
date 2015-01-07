<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePoolNode */

$this->title = 'Create Dcmd Service Pool Node';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Service Pool Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-node-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
