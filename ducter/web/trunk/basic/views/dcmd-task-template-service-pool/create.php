<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskTemplateServicePool */

$this->title = '添加任务模板服务池子';
$this->params['breadcrumbs'][] = ['label' => '任务模板', 'url' => ['dcmd-task-template/index']];
$this->params['breadcrumbs'][] = ['label' =>$task_tmpt_name, 'url' => ['dcmd-task-template/view', 'id'=>$task_tmpt_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['create', 'task_tmpt_id' => $task_tmpt_id]); ?>" method="post">
<div class="dcmd-task-template-service-pool">

    <input type="text" name="task_tmpt_id" value="<?php echo $task_tmpt_id; ?>" style="display:none">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            array('attribute'=>'svr_pool_id','label'=>'服务池子', 'value'=>function($model, $key, $index, $column) { return $model['svr_pool'];},),
        ],
    ]); ?>

    <div >
        <?= Html::submitButton('添加',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>

