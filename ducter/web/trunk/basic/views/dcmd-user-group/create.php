<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\DcmdUserGroup */

$this->title = '添加用户组用户';
$this->params['breadcrumbs'][] = ['label' => $gname, 'url' => ['dcmd-group/view', 'id'=>$gid]];
$this->params['breadcrumbs'][] = $this->title;
?>


<form id="w0" action="<?php echo Url::to(['add',]); ?>" method="post">
<div class="dcmd-user-group-create">
    <input type="text" name="gid" value="<?php echo $gid; ?>" style="display:none">
    <input type="text" name="gname" value="<?php echo $gname; ?>" style="display:none">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'username', 'label'=>'用户名', 'filter'=>false, 'enableSorting'=>false, ),
            array('attribute'=>'uid', 'label' => '部门', 'filter'=>false, 'enableSorting'=>false, 'value' => function($model, $key, $index, $column) { return $model->getDepartment($model['uid']);},),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('添加',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>
