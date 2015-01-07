<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择服务池子';
$this->params['breadcrumbs'][] = ['label' => '服务池子列表', 'url' => ['dcmd-service-pool/index']];
$this->params['breadcrumbs'][] = ['label' => '服务池子', 'url' => ['dcmd-service-pool/view', 'id'=>$svr_pool_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['show-node-list',]); ?>" method="post">
<div class="dcmd-service-pool-node-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <input type="text" name="app_id" value="<?php echo $app_id; ?>" style="display:none">
    <input type="text" name="svr_id" value="<?php echo $svr_id; ?>" style="display:none">
    <input type="text" name="svr_pool_id" value="<?php echO $svr_pool_id; ?>" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'ngroup_id','label'=>'设备池子', 'value'=>function($model, $key, $index, $column) { return $model['ngroup_name'];},),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('下一步',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>
