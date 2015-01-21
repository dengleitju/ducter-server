#ifndef __DCMD_CENTER_SUBTASK_OUTPUT_TASK_H__
#define __DCMD_CENTER_SUBTASK_OUTPUT_TASK_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxTaskBoard.h>
#include "dcmd_tss.h"
#include "dcmd_center_def.h"
#include "dcmd_center_agent_mgr.h"

namespace dcmd {
class DcmdCenterApp;
//UI控制台获取任务执行结果的task对象
class DcmdCenterSubtaskOutputTask : public CwxTaskBoardTask {
 public:
   enum{
     TASK_STATE_WAITING = CwxTaskBoardTask::TASK_STATE_USER
   };
   ///构造函数
   DcmdCenterSubtaskOutputTask(DcmdCenterApp* app, CwxTaskBoard* taskboard):CwxTaskBoardTask(taskboard) {
     client_msg_id_ = 0;
     reply_conn_id_ = 0;
     msg_taskid_ = 0;
     app_ = app;
     recv_msg_ = NULL;
     output_offset_ = 0;
   }
   ///析构函数
   ~DcmdCenterSubtaskOutputTask(){
     if (recv_msg_) CwxMsgBlockAlloc::free(recv_msg_);
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

 public:
  // client的消息id
  uint32_t             client_msg_id_;
  // 回复的连接id
  uint32_t              reply_conn_id_;
  // 回复消息的task id
  uint32_t              msg_taskid_;
  // 查询的agent的ip
  string                agent_ip_;
  // 查询的subtask的id
  string                subtask_id_;
  // 查询的偏移
  uint32_t              output_offset_;
 private:
  // app对象
  DcmdCenterApp*         app_;
  // 收到的agent的回复消息
  CwxMsgBlock*           recv_msg_;
  // 错误信息
  string                 err_msg_;
};
}  // dcmd
#endif

