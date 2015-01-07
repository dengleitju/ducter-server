<?php
include dirname(__FILE__)."/../common/CwxDef.class.php";
///include dirname(__FILE__)."/../common/ProtobufMessage.php";
include dirname(__FILE__)."/../common/CwxPackage.class.php";
include dirname(__FILE__)."/../common/CwxRequest.class.php";
include dirname(__FILE__)."/../common/CwxMsgHead.class.php";
include dirname(__FILE__)."/../common/pb_proto_dcmd_cmn.php";
include dirname(__FILE__)."/../common/pb_proto_dcmd_ui.php";


///错误代码定义
const CWX_MQ_ERR_SUCCESS = 0;///<成功
const CWX_MQ_ERR_ERROR = 1;///<失败


///定义center的用户名密码
const CENTER_USER = 'dcmd';
const CENTER_PASSWD = 'dcmd';

///pack msg
function packMsg($msgType,$taskId, $msg)
{
	$version=1;
	$header = new CwxMsgHead($msgType, strlen($msg), $taskId, 0, $version);
	$result = $header->toNet().$msg;
	return $result;
}


///create send msg
function packCreateMsg($taskType, $data)
{
	$taskId=rand(1, 1000);
    $result = packMsg($taskType, $taskId, $data);	
    return $result;
}


///send msg to center
function sendQueue($host, $port, $type, $msg) {
	$request = new CwxRequest($host, $port);
	$pack=packCreateMsg($type, $msg);
	$ret = $request->connect();
	if($ret === false){
		echo $request->getLastError();
		exit;
	}
	$ret = $request->sendMsg($pack);
	if($ret === false){
		echo $request->getLastError();
        exit; 
        }
	$ret = $request->receiveMsg();
	if($ret === false){
		echo $request->getLastError();
		exit;
	}
	return $ret;
}

function getTaskOutput($host, $port, $subtask_id, $ip, $offset) {
  $ui_task_out = new Dcmd_api_UiTaskOutput();
  $ui_task_out->setClientMsgId(0);
  $ui_task_out->setSubTaskId($subtask_id);
  $ui_task_out->setIp($ip);
  $ui_task_out->setOffset($offset);
  $ui_task_out->setUser(CENTER_USER);
  $ui_task_out->setPasswd(CENTER_PASSWD);
  $msg = $ui_task_out->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_AGENT_SUBTASK_OUTPUT, $msg);
  $ui_task_out_reply = new Dcmd_api_UiTaskOutputReply();
  try{
    $ui_task_out_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_task_out_reply;
}

function getRunningTask($host, $port, $ip, $svr_pool) {
  $ui_agent_running_task = new Dcmd_api_UiAgentRunningTask();
  $ui_agent_running_task->setClientMsgId(0);
  $ui_agent_running_task->setIp($ip);
  $ui_agent_running_task->setSvrPool($svr_pool);
  $ui_agent_running_task->setUser(CENTER_USER);
  $ui_agent_running_task->setPasswd(CENTER_PASSWD);
  $msg = $ui_agent_running_task->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_AGENT_RUNNING_SUBTASK, $msg);
  $ui_agent_running_task_reply = new Dcmd_api_UiAgentRunningTaskReply();
  try{
    $ui_agent_running_task_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_agent_running_task_reply;
}

function getRunningOpr($host, $port, $ip){
  $ui_running_opr = new Dcmd_api_UiAgentRunningOpr();
  $ui_running_opr->setClientMsgId(0);
  $ui_running_opr->setIp($ip);
  $ui_running_opr->setUser(CENTER_USER);
  $ui_running_opr->setPasswd(CENTER_PASSWD);
  $msg = $ui_running_opr->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_AGENT_RUNNING_OPR, $msg);
  $ui_agent_running_opr_reply = new Dcmd_api_UiAgentRunningOprReply();
  try{
    $ui_agent_running_opr_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_agent_running_opr_reply;
}

function execOprCmd($host, $port, $args, $agents) {
  $ui_exec_opr_cmd = new Dcmd_api_UiExecOprCmd();
  $ui_exec_opr_cmd->setClientMsgId(0);
  $ui_exec_opr_cmd->setOprId(0);
  $ui_exec_opr_cmd->setUser(CENTER_USER);
  $ui_exec_opr_cmd->setPasswd(CENTER_PASSWD);
  while(list($key, $val)=each($args)){
    $kv = new Dcmd_api_KeyValue();
    $kv->setKey($key);
    $kv->setValue($val);
    $ui_exec_opr_cmd->appendArgs($kv);
  }
  foreach($agents as $ip) {
    $ui_exec_opr_cmd->appendAgents($ip);
  }
  $msg = $ui_exec_opr_cmd->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_EXEC_OPR, $msg);
  $ui_exec_opr_cmd_reply = new Dcmd_api_UiExecOprCmdReply();
  try{
    $ui_exec_opr_cmd_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_exec_opr_cmd_reply;
}

function getAgentInfo($host, $port, $ips, $version) {
  $ui_agent_info = new Dcmd_api_UiAgentInfo();
  foreach($ips as $ip) {
    $ui_agent_info->appendIps($ip);
  }
  $ui_agent_info->setVersion($version);
  $ui_agent_info->setClientMsgId(0);
  $ui_agent_info->setUser(CENTER_USER);
  $ui_agent_info->setPasswd(CENTER_PASSWD);
  $msg = $ui_agent_info->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_AGENT_INFO, $msg);
  $ui_agent_info_reply = new Dcmd_api_UiAgentInfoReply();
  try{
    $ui_agent_info_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_agent_info_reply;
}

function getInvalidAgent($host, $port) {
  $ui_invalid_agent_info = new Dcmd_api_UiInvalidAgentInfo();
  $ui_invalid_agent_info->setClientMsgId(0);
  $ui_invalid_agent_info->setUser(CENTER_USER);
  $ui_invalid_agent_info->setPasswd(CENTER_PASSWD);
  $msg = $ui_invalid_agent_info->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_INVALID_AGENT, $msg);
  $ui_invalid_agent_info_reply = new Dcmd_api_UiInvalidAgentInfoReply();
  try{
    $ui_invalid_agent_info_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_invalid_agent_info_reply;
}


function getTaskScriptInfo($host, $port, $task_cmd) {
  $ui_task_script_info = new Dcmd_api_UiTaskScriptInfo();
  $ui_task_script_info->setClientMsgId(0);
  $ui_task_script_info->setTaskCmd($task_cmd);
  $ui_task_script_info->setUser(CENTER_USER);
  $ui_task_script_info->setPasswd(CENTER_PASSWD);
  $msg = $ui_task_script_info->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_TASK_CMD_INFO, $msg);
  $ui_task_script_info_reply = new Dcmd_api_UiTaskScriptInfoReply();
  try{
    $ui_task_script_info_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_task_script_info_reply;
}

function getOprScriptInfo($host, $port, $opr_file) {
  $ui_opr_script_info = new Dcmd_api_UiOprScriptInfo();
  $ui_opr_script_info->setClientMsgId(0);
  $ui_opr_script_info->setOprFile($opr_file);
  $ui_opr_script_info->setUser(CENTER_USER);
  $ui_opr_script_info->setPasswd(CENTER_PASSWD);
  $msg = $ui_opr_script_info->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_OPR_CMD_INFO, $msg);
  $ui_opr_script_info_reply = new Dcmd_api_UiOprScriptInfoReply();
  try{
    $ui_opr_script_info_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_opr_script_info_reply;
}

function getAgentTaskProcess($host, $port, $subtids) {
  $ui_agent_task_process = new Dcmd_api_UiAgentTaskProcess();
  $ui_agent_task_process->setClientMsgId(0);
  foreach($subtids as $tid) {
      $ui_agent_task_process->appendSubtaskId($tid);
  }
  $ui_agent_task_process->setUser(CENTER_USER);
  $ui_agent_task_process->setPasswd(CENTER_PASSWD);
  $msg = $ui_agent_task_process->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_SUBTASK_PROCESS, $msg);
  $ui_agent_task_process_reply = new Dcmd_api_UiAgentTaskProcessReply();
  try{
    $ui_agent_task_process_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_agent_task_process_reply;
}

function execTaskCmd($host, $port, $task_id, $uid, $cmd_type, $subtask_id=NULL, $ip=NULL,
  $svr_name=NULL, $svr_pool=NULL, $concurrent_rate = NULL, $task_timeout=NULL, $auto=NULL) {
  $ui_task_cmd = new Dcmd_api_UiTaskCmd();
  $ui_task_cmd->setClientMsgId(0);
  $ui_task_cmd->setTaskId($task_id);
  $ui_task_cmd->setUid($uid);
  $ui_task_cmd->setCmdType($cmd_type);
  if($subtask_id) $ui_task_cmd->setSubtaskId($subtask_id);
  if ($ip) $ui_task_cmd->setIp($ip);
  if ($svr_name) $ui_task_cmd->setSvrName($svr_name);
  if ($svr_pool) $ui_task_cmd->setSvrPool($svr_pool);
  if ($concurrent_rate) $ui_task_cmd->setConcurrentRate($concurrent_rate);
  if ($task_timeout) $ui_task_cmd->setTaskTimeout($task_timeout);
  if ($auto) $ui_task_cmd->setAuto($auto);
  $ui_task_cmd->setUser(CENTER_USER);
  $ui_task_cmd->setPasswd(CENTER_PASSWD);
  $msg = $ui_task_cmd->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_EXEC_TASK, $msg);
  $ui_task_cmd_reply = new Dcmd_api_UiTaskCmdReply();
  try{
    $ui_task_cmd_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_task_cmd_reply;
}

function getAgentHostName($host, $port, $agent_ip){
  $ui_agent_host_name = new Dcmd_api_UiAgentHostName();
  $ui_agent_host_name->setClientMsgId(0);
  $ui_agent_host_name->setAgentIp($agent_ip);
  $ui_agent_host_name->setUser(CENTER_USER);
  $ui_agent_host_name->setPasswd(CENTER_PASSWD);
  $msg = $ui_agent_host_name->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_FETCH_AGENT_HOSTNAME, $msg);
  $ui_agent_host_name_reply = new Dcmd_api_UiAgentHostNameReply();
  try{
    $ui_agent_host_name_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_agent_host_name_reply;
}

function agentValid($host, $port, $agent_ip) {
  $ui_agent_valid = new Dcmd_api_UiAgentValid();
  $ui_agent_valid->setClientMsgId(0);
  $ui_agent_valid->setAgentIp($agent_ip);
  $ui_agent_valid->setUser(CENTER_USER);
  $ui_agent_valid->setPasswd(CENTER_PASSWD);
  $msg = $ui_agent_valid->serializeToString();
  $ret_msg = sendQueue($host, $port, Dcmd_api_DcmdMsgType::MTYPE_UI_AUTH_INVALID_AGENT, $msg);
  $ui_agent_valid_reply = new Dcmd_api_UiAgentValidReply();
  try{
    $ui_agent_valid_reply->parseFromString($ret_msg);
  }catch(Exception $ex) {
    die("Parse err:".$ex->getMessage());
  }
  return $ui_agent_valid_reply; 
}

/*$host="127.0.0.1";
$port = 6667;
{
 print "test task output:\n";
 $task_out = getTaskOutput($host, $port, 0, "127.0.0.1", 0);
 print "state:".$task_out->getState()."\n";
 print "err:".$task_out->getErr()."\n";
 print "---------------------------\n";
}
{
 print "test get invalid agent:\n";
 $invalidAgent = getInvalidAgent($host, $port);
 print "state:".$invalidAgent->getState()."\n"; 
 print "err:".$invalidAgent->getErr()."\n";
 if ($invalidAgent->getState() == 0) {
  $agentInfo = $invalidAgent->getAgentinfo();
  foreach($agentInfo as $agent) {
   print "state:".$agent->getState()."\n";
   print "version:".$agent->getVersion()."\n";
   print "conn_ip:".$agent->getConnectedIp()."\n";
   print "report_ip:".$agent->getReportedIp()."\n";
   print "hostname:".$agent->getHostname()."\n";
  }
 }
 print "-------------------------\n";
}
{
 print "test running sub task:\n";
 $running_task = getRunningTask($host, $port, "127.0.0.1", "test");
 print $running_task->getState()."\n";
 print $running_task->getErr()."\n";
 print "-------------------------\n";
}
{
 print "test get running opr:\n";
 $running_opr = getRunningOpr($host, $port, "127.0.0.1");
 print $running_opr->getState()."\n";
 print $running_opr->getErr()."\n";
 print "-------------------------\n";
}
{
 print "test exec opr cmd:\n";
 $exec_opr = execOprCmd($host, $port, array(), array("127.0.0.1"));
 print $exec_opr->getState()."\n";
 print $exec_opr->getErr()."\n";
 print "-------------------------\n";
}
{
 print "test get agent info:\n";
 $agent_info = getAgentInfo($host, $port, array("127.0.0.1"), 1);
 print $agent_info->getState()."\n";
 print $agent_info->getErr()."\n";
 print "-----------------------\n";
}
{
 print "test get task info:\n";
 $task_info = getTaskScriptInfo($host, $port, "test");
 print $task_info->getState()."\n";
 print $task_info->getErr()."\n";
 print "----------------------\n";
}
{
 print "test get opr info:\n";
 $opr_info = getOprScriptInfo($host ,$port, "test");
 print $opr_info->getState()."\n";
 print $opr_info->getErr()."\n";
 print "------------------\n";
}
{
 print "get sub task proces\n";
 $task_proc = getAgentTaskProcess($host, $port, array(1));
 print $task_proc->getState()."\n";
 print $task_proc->getErr()."\n";
 print "---------------------\n";
}
{
 print "test exec task cmd\n";
 $exec_task = execTaskCmd($host, $port, 1, 2, 1);
 print $exec_task->getState()."\n";
 print $exec_task->getErr()."\n";
 print "----------------------\n";
}
{
 print "get agent host name\n";
 $agent_host = getAgentHostName($host, $port, "127.0.0.1");
 print $agent_host->getState()."\n";
 print $agent_host->getErr()."\n";
 print "-----------------------\n";
}
{
 print "test valid agent\n";
 $vagent = agentValid("127.0.0.1", 6666, "127.0.0.1");
 print $vagent->getState()."\n";
 print $vagent->getErr()."\n";
}
*/
?>
