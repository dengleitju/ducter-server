<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdServicePool */

$this->title = '添加服务池子';
$this->params['breadcrumbs'][] = ['label' => '服务池子列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-pool-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
