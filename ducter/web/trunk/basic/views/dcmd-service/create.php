<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdService */

$this->title = '添加服务';
$this->params['breadcrumbs'][] = ['label' => '服务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-service-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
