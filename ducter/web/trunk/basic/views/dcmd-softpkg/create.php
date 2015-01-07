<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdSoftpkg */

$this->title = 'Create Dcmd Softpkg';
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Softpkgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-softpkg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
