<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskCmd */

$this->title = '添加任务脚本';
$this->params['breadcrumbs'][] = ['label' => '任务脚本', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-cmd-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
