<div style="margin:10px 0px 10px 0px">
     <div id="dispatch" style="overflow-y:auto;overflow-x:hidden; max-height:300px; margin-top:10px "></div>
     <div id="running" style="overflow-y:auto;overflow-x:hidden; max-height:300px; margin-top:10px "></div>
     <div id="timeout" style="overflow-y:auto;overflow-x:hidden;max-height:300px; margin-top:10px "></div>
     <div id="fail" style="overflow-y:auto;overflow-x:hidden; max-height:300px; margin-top:10px "></div>
     <div id="success" style="overflow-y:auto;overflow-x:hidden; max-height:300px; margin-top:10px "></div>
</div>


<script>
     
  var getSubTaskStateList=function(){
      getUrlSub("?r=dcmd-task-async/init-sub-task&task_id=<?php echo $task_id; ?>&svr_pool=all",'dispatch');
      getUrlSub("?r=dcmd-task-async/run-sub-task-list&task_id=<?php echo $task_id; ?>&svr_pool=all",'running');
      getUrlSub("?r=dcmd-task-async/fail-sub-task-list&task_id=<?php echo $task_id; ?>&svr_pool=all",'fail');
      getUrlSub("?r=dcmd-task-async/success-sub-task-list&task_id=<?php echo $task_id; ?>&svr_pool=all",'success');
      hitCount++;
      hitCount>1000?hitCount=3:"";
  };
  getSubTaskStateList();
  var si_statetable;
  clearInterval(si_statetable);
  si_statetable=setInterval(getSubTaskStateList,6000);

 var cmdSubmit = function(val) {
   if (!confirm('确定执行此操作？')) {
     return false;
   }
   $.get(val+"&randt="+new Date().getTime(), function(data) {
      alert(data);
      getSubTaskStateList();
   });
  }
</script>



