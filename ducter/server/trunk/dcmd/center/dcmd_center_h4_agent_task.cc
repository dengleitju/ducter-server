#include "dcmd_center_h4_agent_task.h"
#include <CwxMd5.h>
#include "dcmd_center_app.h"
#include "dcmd_center_h4_check.h"

namespace dcmd {
int DcmdCenterH4AgentTask::onRecvMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv) {
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  string conn_ip = "";
  app_->GetAgentMgr()->GetConnIp(msg->event().getConnId(), conn_ip);
  switch(msg->event().getMsgHeader().getMsgType()){
  case dcmd_api::MTYPE_AGENT_REPORT:
    AgentReport(msg, tss);
    break;
  case dcmd_api::MTYPE_AGENT_HEATBEAT:
    // 此消息已经有通信线程处理
    CWX_ASSERT(0);
    break;
  case dcmd_api::MTYPE_CENTER_MASTER_NOTICE_R:
    AgentMasterReply(msg, tss);
    break;
  case dcmd_api::MTYPE_CENTER_SUBTASK_CMD_R:
    AgentSubtaskAccept(msg, tss);
    break;
  case dcmd_api::MTYPE_AGENT_SUBTASK_CMD_RESULT:
    AgentSubtaskResult(msg, tss);
    break;
  case dcmd_api::MTYPE_AGENT_SUBTASK_PROCESS:
    AgentSubtaskProcess(msg, tss);
    break;
  case dcmd_api::MTYPE_UI_EXEC_TASK:
    UiExecTaskCmd(msg, tss);
    break;
  default:
    CWX_ERROR(("Receive invalid msg type[%d] from host:%s, ignore it.",
      msg->event().getMsgHeader().getMsgType(),
      conn_ip.length()?conn_ip.c_str():"unknown"));
    break;
  }
  return 1;
}

// 连接关闭后，需要清理环境
int DcmdCenterH4AgentTask::onConnClosed(CwxMsgBlock*& msg, CwxTss* pThrEnv) {
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  string agent_ip;
  if (msg->event().getConnUserData()) {
    agent_ip = (char*) msg->event().getConnUserData();
    free(msg->event().getConnUserData());
    app_->GetTaskMgr()->ReceiveAgentClosed(tss, agent_ip);
  }
  return 1;
}
int DcmdCenterH4AgentTask::onTimeoutCheck(CwxMsgBlock*& , CwxTss* pThrEnv) {
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  static uint32_t last_check_time = time(NULL);
  static uint32_t base_time = 0;
  uint32_t now = time(NULL);
  bool is_clock_back = app_->IsClockBack(base_time, now);
  if (!is_clock_back && (now <= last_check_time)) return 1;
  last_check_time = now;
  CWX_INFO(("Agent thread timeout check...."));
  if (app_->is_master()) {
    if (!is_master_ || !app_->GetTaskMgr()->IsStart()) {
      CWX_INFO(("I becomes master, startup task manager......"));
      app_->GetTaskMgr()->Stop(tss);
      is_master_ = app_->GetTaskMgr()->Start(tss);
      if (!is_master_) {
        CWX_ERROR(("Failed to start task manager."));
        app_->GetTaskMgr()->Stop(tss);
      } else {
        // 通知所有agent，自己是master
        CWX_INFO(("Notice all agent that i becomes master......"));
        NoticeMaster(tss, NULL);
      }
    }
    // 调度任务
    if (is_master_) {
      app_->GetTaskMysql()->commit();
      is_master_ = app_->GetTaskMgr()->Schedule(tss);
      if (!is_master_) app_->GetTaskMgr()->Stop(tss);
    }
  }
  // 刷新ip/state表
  app_->GetAgentMgr()->RefreshIpState(now);
  return 1;
}
int DcmdCenterH4AgentTask::onUserEvent(CwxMsgBlock*& , CwxTss* pThrEnv){
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  CWX_INFO(("Receive master change event. app master:%s, handle master:%s",
    app_->is_master()?"true":"false",
    is_master_?"true":"false"));
  if (app_->is_master()){
    // 由于是不同的链接，防止master切换带来问题
    CWX_DEBUG(("Start check task mysql..........."));
    if (!app_->CheckMysql(app_->GetTaskMysql())) return 1;
    string host;
    if (!DcmdCenterH4Check::GetMasterHost(app_->GetTaskMysql(), host, tss)){
      // 关闭mysql的连接
      app_->GetTaskMysql()->disconnect();
      return 1;
    }
    if (host != app_->config().common().host_id_){
      app_->GetTaskMysql()->disconnect();
      return 1;
    }
    if (!is_master_){
      CWX_INFO(("Startup task manager......."));
      app_->GetTaskMgr()->Stop(tss);
      is_master_ = app_->GetTaskMgr()->Start(tss);
      if (!is_master_){
        CWX_ERROR(("Failure to start task manager."));
        app_->GetTaskMgr()->Stop(tss);
      }else{
        ///通知所有agent，自己是master
        CWX_INFO(("Notice all agent that i am master......"));
        NoticeMaster(tss, NULL);
      }
    }
  }else{
    CWX_INFO(("Stop task manager."));
    app_->GetTaskMgr()->Stop(tss);
    app_->GetAgentMgr()->ClearMasterNoticeReportReply();
    is_master_ = false;
  }
  return 1;
}
void DcmdCenterH4AgentTask::ReplyAgentReport(DcmdCenterApp* app,
  DcmdTss*      tss,
  uint32_t      conn_id,
  uint32_t      msg_taskid,
  dcmd_api::AgentReportReply const* reply)
{
  string agent_ip;
  app->GetAgentMgr()->GetAgentIp(conn_id, agent_ip);
  CwxMsgBlock* msg = NULL;
  if (!reply->SerializeToString(&tss->proto_str_)) {
    CWX_ERROR(("Failure to pack agent report msg."));
    app->noticeCloseConn(conn_id);
    return;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_AGENT_REPORT_R, msg_taskid,
    tss->proto_str_.length());
  msg = CwxMsgBlockAlloc::pack(head, tss->proto_str_.c_str(),
    tss->proto_str_.length());
  if (!msg) {
    CWX_ERROR(("Failure to pack agent report reply msg for no memory"));
    exit(1);
  }
  msg->send_ctrl().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT);
  msg->send_ctrl().setConnId(conn_id);
  msg->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (0 != app->sendMsgByConn(msg)){
    CwxMsgBlockAlloc::free(msg);
    CWX_ERROR(("Failure to send msg to agent:%s, close connect.", agent_ip.c_str()));
    app->noticeCloseConn(conn_id);
  }
}
bool DcmdCenterH4AgentTask::SendAgentCmd(DcmdCenterApp* app,
  DcmdTss* tss,
  string const& agent_ip,
  uint32_t msg_taskid,
  dcmd_api::AgentTaskCmd const* cmd)
{
  CwxMsgBlock* msg = NULL;
  if (!cmd->SerializeToString(&tss->proto_str_)) {
    CWX_ERROR(("Failure to pack agent report msg."));
    app->GetAgentMgr()->CloseAgent(agent_ip);
    return false;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_SUBTASK_CMD, msg_taskid,
    tss->proto_str_.length());
  msg = CwxMsgBlockAlloc::pack(head, tss->proto_str_.c_str(),
    tss->proto_str_.length());
  if (!msg) {
    CWX_ERROR(("Failure to pack agent report reply msg for no memory"));
    exit(1);
  }
  msg->send_ctrl().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT);
  msg->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  uint32_t conn_id = 0;
  if (!app->GetAgentMgr()->SendMsg(agent_ip, msg, conn_id)){
    CwxMsgBlockAlloc::free(msg);
    CWX_ERROR(("Failure to send msg to agent:%s, close connect.", agent_ip.c_str()));
    return false;
  }
  return true;
}
bool DcmdCenterH4AgentTask::ReplyAgentCmdResult(DcmdCenterApp* app,
                                 DcmdTss* tss,
                                 uint32_t conn_id,
                                 uint32_t msg_taskid,
                                 dcmd_api::AgentTaskResultReply const* reply)
{
  CwxMsgBlock* msg = NULL;
  if (!reply->SerializeToString(&tss->proto_str_)) {
    CWX_ERROR(("Failure to pack agent report msg."));
    exit(1);
  }
  string agent_ip;
  app->GetAgentMgr()->GetAgentIp(conn_id, agent_ip);
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_AGENT_SUBTASK_CMD_RESULT_R, msg_taskid,
    tss->proto_str_.length());
  msg = CwxMsgBlockAlloc::pack(head, tss->proto_str_.c_str(),
    tss->proto_str_.length());
  if (!msg) {
    CWX_ERROR(("Failure to pack agent report reply msg for no memory"));
    exit(1);
  }
  msg->send_ctrl().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT);
  msg->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (!app->GetAgentMgr()->SendMsg(conn_id, msg)){
    CwxMsgBlockAlloc::free(msg);
    CWX_ERROR(("Failure to send msg to agent:%s.", agent_ip.c_str()));
    return false;
  }
  return true;
}
void  DcmdCenterH4AgentTask::AgentReport(CwxMsgBlock*& msg, DcmdTss* tss){
  string agent_ips;
  string conn_ip="";
  string agent_ip;
  list<string> ips;
  bool is_success = false;
  string err_msg;
  dcmd_api::AgentReport report;
  // 连接已经不存在
  if (!app_->GetAgentMgr()->GetConnIp(msg->event().getConnId(), conn_ip)) return;
  do{
    tss->proto_str_.assign(msg->rd_ptr(), msg->length());
    if (!report.ParseFromString(tss->proto_str_)) {
      CWX_ERROR(("Failure unpack agent report msg from %s, close it.", conn_ip.c_str()));
      err_msg = "Failure unpack agent report msg";
      break;
    }
    // 添加链接ip
    ips.push_back(conn_ip);
    agent_ips = conn_ip;
    for (int i=0; i<report.agent_ips_size(); i++) {
      agent_ips += ",";
      if (conn_ip != report.agent_ips(i)) {
        agent_ips += report.agent_ips(i);
        ips.push_back(report.agent_ips(i));
      }
    }
    CWX_DEBUG(("Receive agent[%s]'s report from %s", agent_ips.c_str(), conn_ip.c_str())); 
    if (!app_->GetAgentMgr()->ComfirmAgentIpByReportedIp(ips, agent_ip)){
      CWX_ERROR(("report agent ip:%s isn't registered , close it.", agent_ips.c_str()));
      err_msg = "report agent ip isn't registered";
      ///添加到无效的agent连接中
      app_->GetAgentMgr()->AddInvalidConn(conn_ip, agent_ips, report.hostname(), report.version());
      break;
    }
    //鉴权
    string old_conn_ip;
    uint32_t old_conn_id=0;
    string old_host_name;
    int ret = app_->GetAgentMgr()->Auth(msg->event().getConnId(),
      agent_ip,
      report.version(),
      agent_ips,
      report.hostname(),
      old_conn_ip,
      old_conn_id,
      old_host_name);
    if (2 == ret){///连接已经关闭
      CWX_ERROR(("Agent[%s] is closed.", agent_ip.c_str()));
      return;
    }
    if (1 == ret){//auth ip exist，可能是非法冒充ip，也可能是重连
      if (old_conn_ip == conn_ip){//关闭旧连接
        app_->GetAgentMgr()->UnAuth(agent_ip);
        app_->GetTaskMgr()->ReceiveAgentClosed(tss, agent_ip);
        app_->noticeCloseConn(old_conn_id);
        ///重新认证
        ret = app_->GetAgentMgr()->Auth(msg->event().getConnId(),
          agent_ip,
          report.version(),
          agent_ips,
          report.hostname(),
          old_conn_ip,
          old_conn_id,
          old_host_name);
        CWX_INFO(("Connection for agent[%s:%s] is duplicate, close the old",
          old_host_name.length()?old_host_name.c_str():"",
          agent_ip.c_str()));
        CWX_ASSERT(1 != ret); ///可能为2，连接不存在
      }else if(conn_ip == agent_ip){///当前的连接就是正确的
        app_->GetAgentMgr()->UnAuth(agent_ip);
        app_->GetTaskMgr()->ReceiveAgentClosed(tss, agent_ip);
        app_->noticeCloseConn(old_conn_id);
        ///重新认证
        ret = app_->GetAgentMgr()->Auth(msg->event().getConnId(),
          agent_ip,
          report.version(),
          agent_ips,
          report.hostname(),
          old_conn_ip,
          old_conn_id,
          old_host_name);
        CWX_INFO(("Old conn[%s] for agent[%s:%s] is invalid, close the old",
          old_conn_ip.c_str(), old_host_name.length()?old_host_name.c_str():"",
          agent_ip.c_str()));
        CWX_ASSERT(1 != ret); ///可能为2，连接不存在
        if (2 == ret) return; ///连接不存在
      } else {
        CWX_INFO(("conn[%s] for agent[%s] is invalid, close it", conn_ip.c_str(), agent_ip.c_str()));
        err_msg = "Failure to auth";
        app_->GetAgentMgr()->AddInvalidConn(conn_ip,agent_ips, report.hostname(), report.version());
        break;
      }
    }
    is_success = true;
  }while(0);
  // 回复
  dcmd_api::AgentReportReply  reply;
  reply.set_state(is_success?dcmd_api::DCMD_STATE_SUCCESS:dcmd_api::DCMD_STATE_FAILED);
  reply.set_err(is_success?err_msg:"");
  reply.set_heatbeat(app_->config().common().heatbeat_internal_);
  reply.set_package_size(app_->config().common().agent_package_size_);
  reply.set_opr_overflow_threshold(app_->config().common().opr_overflow_threshold_);
  ReplyAgentReport(app_,
    tss,
    msg->event().getConnId(),
    msg->event().getMsgHeader().getTaskId(),
    &reply);
  // 如果自己是master，则通知agent
  if (is_success && app_->is_master())  NoticeMaster(tss, &agent_ip);
  return;
}
void  DcmdCenterH4AgentTask::AgentMasterReply(CwxMsgBlock*& msg, DcmdTss* tss){
  string conn_ip;
  string agent_ip;
  // 连接已经关闭
  if (!app_->GetAgentMgr()->GetConnIp(msg->event().getConnId(), conn_ip)) return;
  if (!app_->GetAgentMgr()->GetAgentIp(msg->event().getConnId(), agent_ip)) {
    ///若没有认证，则直接关闭连接
    CWX_ERROR(("Close agent connection with conn-ip[%s] for recieving master-reply but no auth. ",
      conn_ip.c_str()));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  if (!app_->is_master()) {
    CWX_DEBUG(("I'm not master, ignore agent's master-reply."));
    app_->GetTaskMgr()->Stop(tss);
    return;
  }
  int ret = app_->GetAgentMgr()->MasterNoticeReply(msg->event().getConnId());
  if (2 == ret)  return; // 连接已经关闭
  if (1 == ret){// 没有报告自己的agent信息
    CWX_ERROR(("agent[%s] doesn't report self-agent info it.", conn_ip.c_str()));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  dcmd_api::AgentMasterNoticeReply notice_reply;
  tss->proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!notice_reply.ParseFromString(tss->proto_str_)) {
    CWX_ERROR(("Failure unpack master notice reply msg from %s, close it.", agent_ip.c_str()));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  list<string> cmd_list;
  string cmds;
  int i = 0;
  for (i=0; i<notice_reply.cmd_size(); i++){
    cmd_list.push_back(notice_reply.cmd(i));
    if (cmds.length()) cmds += ",";
  }
  CWX_DEBUG(("Receive agent's master-reply, agent:%s, cmd_id=%s", agent_ip.c_str(),
    cmds.c_str()));
  app_->GetTaskMgr()->ReceiveAgentMasterReply(tss, agent_ip, cmd_list);
  for (i=0; i<notice_reply.subtask_process_size(); i++){
    app_->GetTaskMgr()->SetAgentTaskProcess(notice_reply.subtask_process(i).subtask_id(),
      notice_reply.subtask_process(i).process().c_str());
  }
}
void  DcmdCenterH4AgentTask::AgentSubtaskAccept(CwxMsgBlock*& msg, DcmdTss* tss) {
  if (!app_->is_master()) return; // 若不是master，直接忽略
  string agent_ip;
  if (!app_->GetAgentMgr()->GetAgentIp(msg->event().getConnId(), agent_ip))
    return;//连接已经关闭
  dcmd_api::AgentTaskCmdReply cmd_reply;
  tss->proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!cmd_reply.ParseFromString(tss->proto_str_)) {
    CWX_ERROR(("Failure unpack subtask msg from %s, close it.", agent_ip.c_str()));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  CWX_DEBUG(("Receive agent's command-confirm message, agent:%s, cmd_id=%s",
    agent_ip.c_str(),
    cmd_reply.cmd().c_str()));
  app_->GetTaskMgr()->ReceiveAgentSubtaskConfirm(tss, agent_ip, cmd_reply.cmd());
}
void  DcmdCenterH4AgentTask::AgentSubtaskResult(CwxMsgBlock*& msg, DcmdTss* tss){
  if (!app_->is_master()) return; // 若不是master，直接忽略
  string agent_ip="";
  if (!app_->GetAgentMgr()->GetAgentIp(msg->event().getConnId(), agent_ip))
    return; // 连接已经关闭
  dcmd_api::AgentTaskResult result;
  tss->proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!result.ParseFromString(tss->proto_str_)) {
    CWX_ERROR(("Failure unpack subtask result msg from %s, close it.", agent_ip.c_str()));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  CWX_DEBUG(("Receive agent's command result, agent:%s, task_id=%s, subtask_id=%s, cmd_id=%s",
    agent_ip.c_str(),
    result.task_id().c_str(),
    result.subtask_id().c_str(),
    result.cmd().c_str()));
  if (!app_->GetTaskMgr()->ReceiveAgentSubtaskResult(tss, msg->event().getMsgHeader().getTaskId(), result, msg->event().getConnId()))
    app_->noticeCloseConn(msg->event().getConnId()); // 关闭连接以便再处理
}
void DcmdCenterH4AgentTask::AgentSubtaskProcess(CwxMsgBlock*& msg, DcmdTss* tss){
  if (!app_->is_master()) return; //若不是master，直接忽略
  string agent_ip ;
  if (!app_->GetAgentMgr()->GetAgentIp(msg->event().getConnId(), agent_ip))
    return; // 连接已经关闭
  dcmd_api::AgentSubTaskProcess process;
  tss->proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!process.ParseFromString(tss->proto_str_)) {
    CWX_ERROR(("Failure unpack subtask process msg from %s, close it.", agent_ip.c_str()));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  CWX_DEBUG(("Receive agent's subtask-process, agent:%s, task_id=%s, subtask_id=%s, process=%s",
    agent_ip.c_str(), 
    process.task_id().c_str(),
    process.subtask_id().c_str(),
    process.process().c_str()));
  app_->GetTaskMgr()->SetAgentTaskProcess(process.subtask_id(), process.process().c_str());
}
void DcmdCenterH4AgentTask::UiExecTaskCmd(CwxMsgBlock*& msg, DcmdTss* tss){
  if (!app_->is_master()) return; //若不是master，直接忽略
  dcmd_api::UiTaskCmd task_cmd;
  tss->proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!task_cmd.ParseFromString(tss->proto_str_)) {
    CWX_ERROR(("Failure unpack task cmd msg, close it."));
    app_->noticeCloseConn(msg->event().getConnId());
    return;
  }
  CWX_DEBUG(("Receive task cmd, task_id=%s, cmd_type=%d",
    task_cmd.task_id().c_str(),
    task_cmd.cmd_type()));
  if (!app_->GetTaskMgr()->ReceiveCmd(tss, task_cmd, msg->event().getConnId(), msg->event().getMsgHeader().getTaskId())) {
    // 操作数据库错误
    app_->GetTaskMgr()->Stop(tss);
  }
}
void DcmdCenterH4AgentTask::NoticeMaster(DcmdTss* , string const* agent_ip) {
  CwxMsgBlock* msg = NULL;
  CWX_DEBUG(("I am master, notice agent[%s]", agent_ip?agent_ip->c_str():"all"));
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_MASTER_NOTICE, 0, 0);
  msg = CwxMsgBlockAlloc::pack(head, "", 0);
  if (!msg){
    CWX_ERROR(("Failure to pack master notice msg for no memory"));
    CWX_ASSERT(0);
  }
  msg->send_ctrl().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT);
  msg->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  uint32_t conn_id = 0;
  if (!agent_ip) {
    app_->GetAgentMgr()->BroadcastMsg(msg);
  } else {
    if (!app_->GetAgentMgr()->SendMsg(*agent_ip, msg, conn_id))
      CwxMsgBlockAlloc::free(msg);
  }
}
}  // dcmd

