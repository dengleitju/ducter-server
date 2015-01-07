<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdTaskHistory */

$this->title = $model->task_name;
$this->params['breadcrumbs'][] = ['label' => '历史任务', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-task-history-view">



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'task_name:text:任务名称',
            'task_cmd:text:任务类型',
            'svr_name:text:服务名称',
            'svr_path:text:上线路径',
            'tag:text:版本',
            'update_env:text:是否更新环境',
            'update_tag:text:是否更新版本',
            'state:text:状态',
            'freeze:text:是否冻结',
            'valid:text:是否可用',
            'pause',
            'err_msg:text:错误信息',
            'concurrent_rate:text:并发数',
            'timeout:text:超时',
            'auto',
            'process:text:进度',
            'task_arg:ntext:参数',
            'comment:text:说明',
            'utime:text:修改时间',
            'ctime:text:创建时间',
            'opr_uid:text:创建者',
        ],
    ]) ?>

</div>
