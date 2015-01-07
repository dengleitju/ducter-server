<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdOprCmd */

$this->title = $model->opr_cmd_id;
$this->params['breadcrumbs'][] = ['label' => 'Dcmd Opr Cmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-opr-cmd-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->opr_cmd_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->opr_cmd_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'opr_cmd_id',
            'opr_cmd',
            'ui_name',
            'run_user',
            'script_md5',
            'timeout:datetime',
            'comment',
            'utime',
            'ctime',
            'opr_uid',
        ],
    ]) ?>

</div>
