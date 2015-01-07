<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DcmdNode */

$this->title = "设备详细信息:".$model->nid;
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dcmd-node-view">

    <p>
        <?= Html::a('更改', ['update', 'id' => $model->nid], ['class' => 'btn btn-primary']) ?>
<!--        <?= Html::a('删除', ['delete', 'id' => $model->nid], [
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
            'ip:text:服务器IP',
            array('attribute'=>'ngroup_id', 'label'=>'服务器组ID', 'value'=>$model->getNodeGname($model['ngroup_id'])),
            'host:text:主机名',
            'sid:text:资产序列号',
            'did:text:设备序列号',
            'os_type:text:操作系统类型',
            'os_ver:text:操作系统版本号',
            'bend_ip:text:带外IP',
            'public_ip:text:公网IP',
            'mach_room:text:机房',
            'rack:text:机架',
            'seat:text:机位',
            'online_time:text:上线时间',
            'server_brand:text:服务器品牌',
            'server_model:text:服务器型号',
            'cpu:text:CPU信息',
            'memory:text:内存信息',
            'disk:text:磁盘信息',
            'purchase_time:text:采购时间',
            'maintain_time:text:维保时间',
            'maintain_fac:text:维保厂家',
            'utime:text:修改时间',
            'ctime:text:创建时间',
            'opr_uid:text:修改者',
            'comment:text:说明',
        ],
    ]) ?>

</div>
