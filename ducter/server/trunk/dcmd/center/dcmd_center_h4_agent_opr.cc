#include "dcmd_center_h4_agent_opr.h"
#include "dcmd_center_app.h"
namespace dcmd {
int DcmdCenterH4AgentOpr::onConnClosed(CwxMsgBlock*& msg, CwxTss* pThrEnv) {
  list<CwxTaskBoardTask*> tasks;
  app_->getTaskBoard().noticeConnClosed(msg, pThrEnv, tasks);
  if (!tasks.empty()) {
    list<CwxTaskBoardTask*>::iterator iter = tasks.begin();
    while(iter != tasks.end()){
      (*iter)->execute(pThrEnv);
      ++iter;
    }
  }
  return 1;
}
int DcmdCenterH4AgentOpr::onRecvMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv) {
  CwxTaskBoardTask* task = NULL;
  app_->getTaskBoard().noticeRecvMsg(msg->event().getMsgHeader().getTaskId(),
    msg,  pThrEnv, task);
  if (task) task->execute(pThrEnv);
  return 1;
}
int DcmdCenterH4AgentOpr::onEndSendMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv) {
  CwxTaskBoardTask* task = NULL;
  app_->getTaskBoard().noticeEndSendMsg(msg->event().getTaskId(),
    msg, pThrEnv, task);
  if (task) task->execute(pThrEnv);
  return 1;
}
int DcmdCenterH4AgentOpr::onFailSendMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv) {
  CwxTaskBoardTask* task = NULL;
  app_->getTaskBoard().noticeFailSendMsg(msg->event().getTaskId(),
    msg, pThrEnv, task);
  if (task) task->execute(pThrEnv);
  return 1;
}
bool DcmdCenterH4AgentOpr::SendAgentMsg(DcmdCenterApp* app,
  string const& agent_ip, uint32_t msg_task_id,
  CwxMsgBlock*& msg, uint32_t& conn_id)
{
  msg->send_ctrl().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT);
  msg->send_ctrl().setMsgAttr(CwxMsgSendCtrl::FAIL_FINISH_NOTICE);
  msg->event().setTaskId(msg_task_id);
  msg->event().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT_OPR);
  return app->GetAgentMgr()->SendMsg(agent_ip, msg, conn_id);
}
}  // dcmd

