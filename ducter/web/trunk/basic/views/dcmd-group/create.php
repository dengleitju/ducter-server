<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */

$this->title = '添加用户组';
$this->params['breadcrumbs'][] = ['label' => '用户组', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-create">

    <?= $this->render('_form', [
        'model' => $model,
        'disabled' => '',
    ]) ?>

</div>
