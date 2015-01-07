<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdServicePoolNodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择服务池子设备';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['dcmd-task/index']];
$this->params['breadcrumbs'][] = ['label' => $task_name, 'url' => ['dcmd-task-async/monitor-task', 'task_id'=>$task_id]]; 
$this->params['breadcrumbs'][] = $this->title;
?>

<form id="w0" action="<?php echo Url::to(['add-service-pool-node',]); ?>" method="post">
<div class="dcmd-service-pool-node-index">

    <input type="text" name="task_id" value="<?php echo $task_id; ?>" style="display:none">
    <input class="checkAll" type="checkbox" checked>全选

    <?php foreach($service_pool as $item) {
     echo "<div><a href=\"javascript:void(0);\" onclick=\"showServicePoolNode('".$item['svr_pool']."Node')\" style=\"margin:0px 2px 0px 2px\">";
     echo "应用池子服务器详情</a>，名称：".$item['svr_pool'];
     echo "<input name='AppSvr".$item['svr_pool']."'  type=\"checkbox\" checked  value='".$item['svr_pool']."' class='checkAll' style=\"display: none\"/>";
     echo "<div id='".$item['svr_pool']."Node'  style=\"display:none\">";
     $i = 0;
     foreach($item['ip'] as $ip) {
      echo "<input  class='checkItems'  name='SvrPoolNode".$item['svr_pool'].$ip."' type=\"checkbox\" checked value='$ip' />$ip";
     $i = $i + 1;
     if($i % 8 == 0) echo "<br>";
     }
     echo "</div></div>"; 
    } ?>

    <div >
        <?= Html::submitButton('下一步',   ['class' => 'btn btn-success' ])?>
    </div>
</div>
</form>

<script language="JavaScript">
function showServicePoolNode(appPool){
 if( document.getElementById(appPool).style.display=='none'){
     document.getElementById(appPool).style.display='block';
 }else{
     document.getElementById(appPool).style.display='none';
 }
        
}
</script>

