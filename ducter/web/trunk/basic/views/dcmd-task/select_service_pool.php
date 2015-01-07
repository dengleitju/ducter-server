<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '添加服务池子';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['dcmd-task/index']];
$this->params['breadcrumbs'][] = ['label' => $task_name, 'url' => ['dcmd-task-async/monitor-task', 'task_id'=>$task_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['select-service-pool-node',]); ?>" method="post">
<div class="dcmd-service-pool-select">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <input type="text" name="task_id" value="<?php echo $task_id; ?>" style="display:none">
    <input type="text" name="task_cmd" value="<?php echo $task_cmd; ?>" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'svr_pool_id','label'=>'服务池子', 'value'=>function($model, $key, $index, $column) { return $model['svr_pool'];},),
            array('attribute'=>'env_ver', 'label'=>'池子配置版本'),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('下一步',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>
