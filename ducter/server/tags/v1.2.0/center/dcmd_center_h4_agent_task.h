#ifndef __DCMD_CENTER_H4_AGENT_TASK_H__
#define __DCMD_CENTER_H4_AGENT_TASK_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxCommander.h>
#include "dcmd_tss.h"
#include "dcmd_center_def.h"

namespace dcmd {
class DcmdCenterApp;
// 处理来自agent的任务指令消息的处理handler
class DcmdCenterH4AgentTask: public CwxCmdOp{
 public:
  DcmdCenterH4AgentTask(DcmdCenterApp* app):app_(app) {
    is_master_ = false;
  }
  virtual ~DcmdCenterH4AgentTask() { }

 public:
  // 处理收到消息的事件
  virtual int onRecvMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv);
  // 连接关闭后，需要清理环境
  virtual int onConnClosed(CwxMsgBlock*& msg, CwxTss* pThrEnv);
  // 超时监测
  virtual int onTimeoutCheck(CwxMsgBlock*& msg, CwxTss* pThrEnv);
  // 用户定义的消息
  virtual int onUserEvent(CwxMsgBlock*& msg, CwxTss* pThrEnv);

 public:
  // 回复agent的report
  static void ReplyAgentReport(DcmdCenterApp* app,
    DcmdTss*      tss,
    uint32_t      conn_id,
    uint32_t      msg_taskid,
    dcmd_api::AgentReportReply const* reply
    );
  // 向agent发送命令。返回值，false：发送失败；true：发送成功。
  static bool SendAgentCmd(DcmdCenterApp* app,
    DcmdTss* tss,
    string const& agent_ip,
    uint32_t msg_taskid,
    dcmd_api::AgentTaskCmd const* cmd);
  // 回复agent的cmd的结果。返回值，false：发送失败；true：发送成功
  static bool ReplyAgentCmdResult(DcmdCenterApp* app,
    DcmdTss* tss,
    uint32_t conn_id,
    uint32_t msg_taskid,
    dcmd_api::AgentTaskResultReply const* reply
    );

 private:
  // agent报告自己的状态
  void  AgentReport(CwxMsgBlock*& msg, DcmdTss* tss);
  // agent报告自己的任务处理状态
  void  AgentMasterReply(CwxMsgBlock*& msg, DcmdTss* tss);
  // agent报告自己已经接受子任务
  void  AgentSubtaskAccept(CwxMsgBlock*& msg, DcmdTss* tss);
  // agent回复任务的处理结果
  void  AgentSubtaskResult(CwxMsgBlock*& msg, DcmdTss* tss);
  // agent报告任务的处理进度
  void  AgentSubtaskProcess(CwxMsgBlock*& msg, DcmdTss* tss);
  // 接收到UI命令
  void  UiExecTaskCmd(CwxMsgBlock*& msg, DcmdTss* tss);
  // 通知所有连接，自己是master
  void  NoticeMaster(DcmdTss* tss, string const* agent_ip=NULL);

 private:
  // app对象
  DcmdCenterApp*             app_;
  // 当前是否为master
  bool                       is_master_;
};
}  // dcmd
#endif 

