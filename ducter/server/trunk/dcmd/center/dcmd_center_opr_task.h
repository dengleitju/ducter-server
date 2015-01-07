#ifndef __DCMD_CENTER_OPR_TASK_H__
#define __DCMD_CENTER_OPR_TASK_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxTaskBoard.h>
#include "dcmd_tss.h"
#include "dcmd_center_def.h"
namespace  dcmd {
  class DcmdCenterApp;
  // agent的操作指令执行结果的对象
  class DcmdCenterAgentOprReply {
  public:
    DcmdCenterAgentOprReply() {
      recv_msg_ = NULL;
      is_send_failed_ = false;
      is_exec_success = true;
      status_ = 0;
    }
    ~DcmdCenterAgentOprReply(){
      if (recv_msg_) CwxMsgBlockAlloc::free(recv_msg_);
    }

  public:
    // 收到的回复
    CwxMsgBlock*        recv_msg_;
    // 是否发送失败，若没有失败而且recv_msg_为空，则表示超时
    bool                is_send_failed_;
    // 对方执行是否成功
    bool                is_exec_success;
    // 执行的退出代码
    int                 status_;
    // 执行的结果
    string              result_;
    // 执行的错误信息
    string              err_msg_; 
  };

// UI控制台的操作命令的task对象
class DcmdCenterOprTask : public CwxTaskBoardTask {
 public:
  enum{
    // 命令不可循环执行
    OPR_CMD_NO_REPEAT = 0,
    // 命令循环执行而且不记录历史
    OPR_CMD_REPEAT_NO_HISTORY = 1,
    // 命令循环执行而且记录历史
    OPR_CMD_REPEAT_HISTORY = 2
  };
  enum{
    TASK_STATE_WAITING = CwxTaskBoardTask::TASK_STATE_USER
  };
  DcmdCenterOprTask(DcmdCenterApp* app, CwxTaskBoard* taskboard, bool is_dup):CwxTaskBoardTask(taskboard), is_dup_opr_(is_dup) {
    client_msg_id_ = 0;
    reply_conn_id_ = 0;
    msg_task_id_ = 0;
    opr_cmd_id_ = 0;
    is_reply_timeout_ = false;
    is_failed_ = false;
    agent_num_ = 0;
    receive_num_ = 0;
    agent_conns_ = NULL;
    agent_replys_ = NULL;
    app_ = app;
  }
  ///析构函数
  ~DcmdCenterOprTask() {
    if (agent_conns_) delete [] agent_conns_;
    if (agent_replys_) delete [] agent_replys_;
  }
 public:
    /**
    @brief 通知Task已经超时
    @param [in] pThrEnv 调用线程的Thread-env
    @return void
    */
    virtual void noticeTimeout(CwxTss* pThrEnv);
    /**
    @brief 通知Task的收到一个数据包。
    @param [in] msg 收到的消息
    @param [in] pThrEnv 调用线程的Thread-env
    @param [out] bConnAppendMsg 收到消息的连接上，是否还有待接收的其他消息。true：是；false：没有
    @return void
    */
    virtual void noticeRecvMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv, bool& bConnAppendMsg);
    /**
    @brief 通知Task往外发送的一个数据包发送失败。
    @param [in] msg 收到的消息
    @param [in] pThrEnv 调用线程的Thread-env
    @return void
    */
    virtual void noticeFailSendMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv);
    /**
    @brief 通知Task通过某条连接，发送了一个数据包。
    @param [in] msg 发送的数据包的信息
    @param [in] pThrEnv 调用线程的Thread-env
    @param [out] bConnAppendMsg 发送消息的连接上，是否有等待回复的消息。true：是；false：没有
    @return void
    */
    virtual void noticeEndSendMsg(CwxMsgBlock*& msg, CwxTss* pThrEnv, bool& bConnAppendMsg);
    /**
    @brief 通知Task等待回复消息的一条连接关闭。
    @param [in] uiSvrId 关闭连接的SVR-ID
    @param [in] uiHostId 关闭连接的HOST-ID
    @param [in] uiConnId 关闭连接的CONN-ID
    @param [in] pThrEnv 调用线程的Thread-env
    @return void
    */
    virtual void noticeConnClosed(CWX_UINT32 uiSvrId, CWX_UINT32 uiHostId, CWX_UINT32 uiConnId, CwxTss* pThrEnv);
    /**
    @brief 激活Task。在Task启动前，Task有Task的创建线程所拥有。
    在启动前，Task可以接受自己的异步消息，但不能处理。
    此时有Taskboard的noticeActiveTask()接口调用的。
    @param [in] pThrEnv 调用线程的Thread-env
    @return 0：成功；-1：失败
    */
    virtual int noticeActive(CwxTss* pThrEnv);
    /**
    @brief 执行Task。在调用此API前，Task在Taskboard中不存在，也就是说对别的线程不可见。
    Task要么是刚创建状态，要么是完成了前一个阶段的处理，处于完成状态。
    通过此接口，由Task自己控制自己的step的跳转而外界无需关系Task的类型及处理过程。
    @param [in] pTaskBoard 管理Task的Taskboard
    @param [in] pThrEnv 调用线程的Thread-env
    @return void
    */
    virtual void execute(CwxTss* pThrEnv);
 
 private:
    void Reply(CwxTss* pThrEnv);
    ///从数据库中获取opr指令信息；true：成功；false：失败
    bool FetchOprCmd(DcmdTss* tss);
    ///从数据库中获取dup opr指令信息；true：成功；false：失败
    bool FetchDupOprCmd(DcmdTss* tss);
    ///检查脚本是否改变
    bool IsScriptChanged(DcmdTss* tss);
 public:
  // client的消息id
  uint32_t                     client_msg_id_;
  // 回复的连接ID
  uint32_t                     reply_conn_id_;
  // 接收到消息的TaskId
  uint32_t                     msg_task_id_;
  // 消息的cmd id
  uint64_t                     opr_cmd_id_;
  // dup 名字
  string                       dup_opr_name_;
  // 执行的agent
  set<string>                  agents_;
  // 执行的参数
  map<string, string>          opr_args_;
 private:
   // 是否dup操作
  const bool                   is_dup_opr_;
  // opr的指令对象
  DcmdCenterOprCmd             opr_cmd_;
  // 是否回复超时
  bool                         is_reply_timeout_;
  // 是否中间操作出错
  bool                         is_failed_;
  // 若是中间出错，此记录错误信息
  string                       err_msg_;
  // 执行opr命令的agnet的数量
  uint16_t                     agent_num_;
  // 收到的回复的数量，发送失败也算回复
  uint16_t                     receive_num_;
  // agent对象数组
  DcmdAgentConnect*            agent_conns_;
  // agent的回复
  DcmdCenterAgentOprReply*     agent_replys_;
  // app对象
  DcmdCenterApp*               app_;
};
}  // dcmd
#endif

