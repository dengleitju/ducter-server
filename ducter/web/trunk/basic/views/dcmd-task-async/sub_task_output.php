<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DcmdAppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '节点信息输出';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/dcmd/assets/7d43d3e8/jquery.js"></script>
<script src="/dcmd/assets/562e379f/yii.js"></script>
<script src="/dcmd/assets/19f3a78/js/bootstrap.js"></script>
<script type="text/javascript">
var si_getmsg;
$(function(){
  $("#feed_list").hover(function () {
      $(this).attr("name", "hovered");
  }, function () {
      $(this).removeAttr("name");
  }); 
  si_getmsg=setInterval("getMsg()", 3000);
});
function getMsg()
{
///alert($("#feed_list div:last").attr("id"));
   var url = "index.php?r=dcmd-task-async/subtask-output&randt="+new Date().getTime()+"&subtask_id=<?php echo $subtask_id; ?>&ip=<?php echo $ip; ?>&os="+ $("#feed_list div:last").attr("id");
   $.get(url, {}, function(html){
    ///alert(html);          
     msgmove(html);
   },'html');
}

function msgmove(html) {
  var isIE = !!window.ActiveXObject;
  var isIE6 = isIE && !window.XMLHttpRequest;

  if ($("#feed_list").attr("name") != "hovered") {
     $("#feed_list").append(html);
  }

  if ($("#feed_list").children().length > 40) {
    $("#feed_list").children("li:gt(30)").remove();
  } 
}
getMsg();
</script>

<!---任务基本信息 --->
<div class="dcmd-app-index">
<table class="table table-striped table-bordered">
<tbody>
<tr><td>任务名称:</td><td><?php echo $task_name; ?></td></tr>
<tr><td>任务类型:</td><td><?php echo $task_cmd; ?></td></tr>
<tr><td>设备IP:</td><td><?php echo $ip; ?></td></tr>
</tbody></table>
任务输出:
<div id="ShellRunResultDiv"     style="height: auto; width: 800px; min-height:1000px; background-color: #000; color: #FFF; padding: 10px 3px 10px 10px">
 <div id="ShellRunResult" style="margin: 10px 0px 10px 10px; overflow-y: auto; height: auto; overflow-x: hidden">
   <div iswrite="1" id="feed_list" class="MIB_feed" _num="13">
      <div id="0"></div>
   </div>
 </div>
</div>

