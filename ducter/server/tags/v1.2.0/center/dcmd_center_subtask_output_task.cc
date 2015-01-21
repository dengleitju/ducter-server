#include "dcmd_center_subtask_output_task.h"
#include "dcmd_center_app.h"
namespace dcmd {
void DcmdCenterSubtaskOutputTask::noticeTimeout(CwxTss* ) {
  err_msg_ = "Timeout";
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  CWX_DEBUG(("Subtask-output task is timeout , task_id=%u, ip=%s",
    getTaskId(), agent_ip_.c_str()));
}
void DcmdCenterSubtaskOutputTask::noticeRecvMsg(CwxMsgBlock*& msg, CwxTss* , bool& ) {
  recv_msg_ = msg;
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  msg = NULL;
}
void DcmdCenterSubtaskOutputTask::noticeFailSendMsg(CwxMsgBlock*& , CwxTss* ) {
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  err_msg_ = string("Failure to send msg to agent:") + agent_ip_;
}
void DcmdCenterSubtaskOutputTask::noticeEndSendMsg(CwxMsgBlock*& , CwxTss* , bool& ){
}
void DcmdCenterSubtaskOutputTask::noticeConnClosed(CWX_UINT32 , CWX_UINT32 , CWX_UINT32 , CwxTss*){
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  err_msg_ = string("Connection is closed. agent:") + agent_ip_;
}
int DcmdCenterSubtaskOutputTask::noticeActive(CwxTss* ThrEnv) {
  DcmdTss* tss= (DcmdTss*)ThrEnv;
  setTaskState(TASK_STATE_WAITING);
  CwxMsgBlock* msg=NULL;
  dcmd_api::AgentTaskOutput query;
  query.set_subtask_id(subtask_id_);
  query.set_offset(output_offset_);
  query.set_ip(agent_ip_);
  if (!query.SerializeToString(&tss->proto_str_)) {
    err_msg_ = "Failure to package subtask-output package";
    CWX_ERROR((err_msg_.c_str()));
    setTaskState(TASK_STATE_FINISH);
    return -1;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_AGENT_SUBTASK_OUTPUT, getTaskId(),
    tss->proto_str_.length());
  msg = CwxMsgBlockAlloc::pack(head, tss->proto_str_.c_str(), tss->proto_str_.length());
  if (!msg){
    err_msg_ = "Failure to package subtask-output package";
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
void DcmdCenterSubtaskOutputTask::execute(CwxTss* pThrEnv) {
  if (CwxTaskBoardTask::TASK_STATE_INIT == getTaskState()) {
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
void DcmdCenterSubtaskOutputTask::Reply(CwxTss* pThrEnv) {
  dcmd_api::UiTaskOutputReply reply;
  dcmd_api::AgentTaskOutputReply agent_reply;
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  reply.set_client_msg_id(client_msg_id_);
  if (!recv_msg_) {
    reply.set_state(dcmd_api::DCMD_STATE_FAILED);
    reply.set_result("");
    reply.set_offset(0);
    reply.set_err(err_msg_);
  }else{
    tss->proto_str_.assign(recv_msg_->rd_ptr(), recv_msg_->length());
    if (!agent_reply.ParseFromString(tss->proto_str_)) {
      reply.set_state(dcmd_api::DCMD_STATE_FAILED);
      reply.set_result("");
      reply.set_offset(0);
      reply.set_err("Failed to parse agent's msg");
    } else {
      reply.set_state(agent_reply.state());
      reply.set_result(agent_reply.result());
      reply.set_offset(agent_reply.offset());
      reply.set_err(agent_reply.err());
    }
  }
  DcmdCenterH4Admin::ReplySubTaskOutput(app_,
    tss,
    reply_conn_id_,
    msg_taskid_,
    &reply);
}
}  // dcmd

