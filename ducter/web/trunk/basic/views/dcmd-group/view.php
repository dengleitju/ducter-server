<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdGroup */

$this->title = $model->gname;
$this->params['breadcrumbs'][] = ['label' => '用户组', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-group-view">

    <p>
        <?= Html::a('更改', ['update', 'id' => $model->gid], ['class' => 'btn btn-primary']) ?>
<!--        <?= Html::a('删除', ['delete', 'id' => $model->gid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            array('attribute'=>'gname', 'label'=>'用户组'),
            array('attribute'=>'gtype', 'label'=>'组类型', 'value'=>$model->convertGtype($model['gtype'])),
            array('attribute'=>'comment', 'label'=>'说明'),
        ],
    ]) ?>


    <p>
        <?= Html::a('用户组用户', Url::to(['dcmd-user-group/index', 'gid'=>$model['gid']]), ['class' => 'btn btn-success', ]) ?>
        <?= Html::a('用户组操作', Url::to(['dcmd-group-cmd/index', 'gid' => $model['gid'], ]), ['class' => 'btn btn-success', ]) ?>
        <?= Html::a('用户组重复操作', Url::to(['dcmd-group-repeat-cmd/index', 'gid' => $model['gid']]), ['class' => 'btn btn-success', ]) ?>
    </p>

</div>
