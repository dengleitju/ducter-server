#include "dcmd_center_run_subtask_task.h"
#include "dcmd_center_app.h"
namespace dcmd {
void DcmdCenterRunSubtaskTask::noticeTimeout(CwxTss* ) {
  err_msg_ = "Timeout";
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  CWX_DEBUG(("Run-subtask Task is timeout , task_id=%u, ip=%s, service_pool=%s",
    getTaskId(),  agent_ip_.c_str(), svr_pool_name_.length()?svr_pool_name_.c_str():""));
}
void DcmdCenterRunSubtaskTask::noticeRecvMsg(CwxMsgBlock*& msg, CwxTss* , bool&) {
  recv_msg_ = msg;
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  msg = NULL;
}
void DcmdCenterRunSubtaskTask::noticeFailSendMsg(CwxMsgBlock*& , CwxTss*) {
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  err_msg_ = string("Failure to send msg to agent:") + agent_ip_;
}
void DcmdCenterRunSubtaskTask::noticeEndSendMsg(CwxMsgBlock*& , CwxTss* , bool& ) {
}
void DcmdCenterRunSubtaskTask::noticeConnClosed(CWX_UINT32 , CWX_UINT32 , CWX_UINT32 , CwxTss*) {
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  err_msg_ = string("Connection is closed. agent:") + agent_ip_;
}
int DcmdCenterRunSubtaskTask::noticeActive(CwxTss* ThrEnv) {
  DcmdTss* tss= (DcmdTss*)ThrEnv;
  setTaskState(TASK_STATE_WAITING);
  CwxMsgBlock* msg=NULL;
  dcmd_api::AgentRunningTask query;
  query.set_ip(agent_ip_);
  query.set_app_name(app_name_);
  query.set_svr_name(svr_name_);
  query.set_svr_pool(svr_pool_name_);
  if (!query.SerializeToString(&tss->proto_str_)) {
    err_msg_ = "Failure to package run subtask package";
    CWX_ERROR((err_msg_.c_str()));
    setTaskState(TASK_STATE_FINISH);
    return -1;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_AGENT_RUNNING_TASK, getTaskId(),
    tss->proto_str_.length());
  msg = CwxMsgBlockAlloc::pack(head, tss->proto_str_.c_str(), tss->proto_str_.length());
  if (!msg){
    err_msg_ = "Failure to package run subtask package";
    CWX_ERROR((err_msg_.c_str()));
    setTaskState(TASK_STATE_FINISH);
    return -1;
  }
  uint32_t conn_id = 0;
  ///发送msg
  if (!DcmdCenterH4AgentOpr::SendAgentMsg(app_,
    agent_ip_,
    getTaskId(),
    msg,
    conn_id))
  {
    err_msg_ = string("Failure to send msg to agent:") + agent_ip_;
    CWX_ERROR((err_msg_.c_str()));
    CwxMsgBlockAlloc::free(msg);
    setTaskState(TASK_STATE_FINISH);
  }
  return 0;
}
void DcmdCenterRunSubtaskTask::execute(CwxTss* pThrEnv) {
  if (CwxTaskBoardTask::TASK_STATE_INIT == getTaskState()){
    recv_msg_ = NULL;
    err_msg_ = "";
    uint64_t timestamp = 3;
    timestamp *= 1000000;
    timestamp += CwxDate::getTimestamp();
    this->setTimeoutValue(timestamp);
    getTaskBoard()->noticeActiveTask(this, pThrEnv);
  }
  if (CwxTaskBoardTask::TASK_STATE_FINISH == getTaskState()){
    Reply(pThrEnv);
    delete this;
  }
}
void DcmdCenterRunSubtaskTask::Reply(CwxTss* pThrEnv) {
  dcmd_api::UiAgentRunningTaskReply reply;
  dcmd_api::AgentRunningTaskReply agent_reply;
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  reply.set_client_msg_id(client_msg_id_);
  if (!recv_msg_) {
    reply.set_state(dcmd_api::DCMD_STATE_FAILED);
    reply.set_err(err_msg_);
  }else{
    tss->proto_str_.assign(recv_msg_->rd_ptr(), recv_msg_->length());
    if (!agent_reply.ParseFromString(tss->proto_str_)) {
      reply.set_state(dcmd_api::DCMD_STATE_FAILED);
      reply.set_err("Failed to parse agent's msg");
    } else {
      dcmd_api::SubTaskInfo* subtask_info = NULL;
      reply.set_state(agent_reply.state());
      if (agent_reply.has_err()) reply.set_err(agent_reply.err());
      for (int i=0; i<agent_reply.result_size(); i++) {
        subtask_info = reply.add_result();
        if (subtask_info) subtask_info->CopyFrom(agent_reply.result(i));
      }
    }
  }
  DcmdCenterH4Admin::ReplyAgentRunSubTask(app_,
    tss,
    reply_conn_id_,
    msg_taskid_,
    &reply);
}
}

