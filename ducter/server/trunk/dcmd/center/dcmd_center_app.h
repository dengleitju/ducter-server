#ifndef __DCMD_CENTER_APP_H__
#define __DCMD_CENTER_APP_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include "CwxAppFramework.h"
#include "dcmd_center_agent_mgr.h"
#include "dcmd_center_config.h"
#include "dcmd_center_def.h"
#include "dcmd_center_h4_admin.h"
#include "dcmd_center_h4_agent_opr.h"
#include "dcmd_center_h4_agent_task.h"
#include "dcmd_center_h4_check.h"
#include "dcmd_center_opr_cache.h"
#include "dcmd_center_opr_task.h"
#include "dcmd_center_run_opr_task.h"
#include "dcmd_center_run_subtask_task.h"
#include "dcmd_center_subtask_output_task.h"
#include "dcmd_center_task_mgr.h"
#include "dcmd_mysql.h"
#include "dcmd_xml_parse.h"

namespace dcmd {
  // 服务版本信息定义
  char const* const kDcmdCenterVersion = "1.0.2";
  char const* const kDcmdCenterModifyDate = "2015-01-4 13:30:08";
  uint32_t const kDcmdMysqlConnectTimeout = 3;
  // Center服务的app对象
  class DcmdCenterApp : public CwxAppFramework {
  public:
    enum{
      // 从控制台接收到的命令的svr类型
      SVR_TYPE_ADMIN = CwxAppFramework::SVR_TYPE_USER_START + 1,
      // 与agent task相关的通信的svr类型
      SVR_TYPE_AGENT = CwxAppFramework::SVR_TYPE_USER_START + 2,
      // agent 操作指令处理的svr类型
      SVR_TYPE_AGENT_OPR = CwxAppFramework::SVR_TYPE_USER_START + 3,
      // Master check的服务的svr类型
      SVR_TYPE_MASTER_CHECK = CwxAppFramework::SVR_TYPE_USER_START + 5,
      // master改变的事件类型
      EVENT_TYPE_MASTER_CHANGE = CwxEventInfo::SYS_EVENT_NUM + 1
    };
    DcmdCenterApp();
    virtual ~DcmdCenterApp();
    virtual int init(int argc, char** argv);
  public:
    // 时钟响应函数
    virtual void onTime(CwxTimeValue const& current);
    // signal响应函数
    virtual void onSignal(int signum);
    // 连接建立
    virtual int onConnCreated(CwxAppHandler4Msg& conn,
      bool& bSuspendConn,
      bool& bSuspendListen);
    // 连接关闭
    virtual int onConnClosed(CwxAppHandler4Msg& conn);
    // 收到消息的响应函数
    virtual int onRecvMsg(CwxMsgBlock* msg,
      CwxAppHandler4Msg& conn,
      CwxMsgHead const& header,
      bool& bSuspendConn);
    // 消息发送失败
    virtual void onFailSendMsg(CwxMsgBlock*& msg);
    //消息发送成功
    virtual CWX_UINT32 onEndSendMsg(CwxMsgBlock*& msg, CwxAppHandler4Msg& conn);

  public:
    // 连接数据库的函数
    bool ConnectMysql(Mysql* my, uint32_t timeout=kDcmdMysqlConnectTimeout);
    // 检查mysql连接，若没有连接则连接，返回false表示连接失败
    bool CheckMysql(Mysql* my);
    // 检测计算机的时钟是否回调
    static bool IsClockBack(uint32_t& last_time, uint32_t now);

  public:
    // 获取配置信息对象
    inline DcmdCenterConf const& config() const { return config_;}
    // 获取admin 操作的mysql句柄
    inline Mysql* GetAdminMysql() { return admin_mysql_; }
    // 获取task操作的mysql句柄
    inline Mysql* GetTaskMysql() { return task_mysql_; }
    // 获取check操作的mysql句柄
    inline Mysql* GetCheckMysql() { return check_mysql_; }
    // 获取自己是否为master
    inline bool is_master() {
      CwxMutexGuard<CwxMutexLock> lock(&lock_);
      return is_master_;
    }
    // 设置是否为master
    inline void SetMaster(bool is_master=true) {
      CwxMutexGuard<CwxMutexLock> lock(&lock_);
      is_master_ = is_master;
    }
    // 获取master主机
    inline void master_host(string & master) {
      CwxMutexGuard<CwxMutexLock> lock(&lock_);
      master = master_host_;
    }
    // 设置master主机
    inline void SetMasterHost(char const* master_host) {
      CwxMutexGuard<CwxMutexLock> lock(&lock_);
      master_host_ = master_host;
    }
    ///获取任务管理对象
    inline DcmdCenterTaskMgr* GetTaskMgr() { return task_mgr_; }
    // 获取opr cmd的cache对象
    inline DcmdCenterOprCache* GetOprCmdCache() { return opr_cmd_cache_; }
    // 获取连接管理器对象
    inline DcmdCenterAgentMgr* GetAgentMgr() { return agent_mgr_; }
    // 获取任务的线程池
    inline CwxThreadPool* GetTaskThreadPool() { return task_thread_pool_; }
  protected:
    virtual int initRunEnv();
    virtual void destroy();
  private:
    // 配置文件
    DcmdCenterConf               config_;
    // admin线程的数据库句柄
    Mysql                        *admin_mysql_;
    // 任务操作的数据库句柄
    Mysql                        *task_mysql_;
    // 主线程的数据库句柄
    Mysql                        *check_mysql_;
    // 保护is_master_ 和 master_host_
    CwxMutexLock                 lock_;
    // 自己是否为master
    bool                         is_master_;
    // master主机
    string                       master_host_;
    // 来自agent事件的处理handler，由task线程处理
    DcmdCenterH4AgentTask        *agent_task_handler_;
    // 对agent进行操作的事件处理handler，由admin线程处理
    DcmdCenterH4AgentOpr         *agent_opr_handler_;
    // 来自控制台的的事件处理handler，由admin线程处理
    DcmdCenterH4Admin            *admin_handler_;
    // master检测的handler，有check线程处理
    DcmdCenterH4Check            *check_handler_;
    // 任务管理对象
    DcmdCenterTaskMgr            *task_mgr_;
    // opr cmd的cache对象
    DcmdCenterOprCache           *opr_cmd_cache_;
    // agent的管理对象
    DcmdCenterAgentMgr           *agent_mgr_;
    // 任务的线程池
    CwxThreadPool                *task_thread_pool_;
    // 管理的线程池
    CwxThreadPool                *admin_thread_pool_;
    // master check线程池
    CwxThreadPool                *check_thread_pool;
    // center的启动时间
    string                       start_datetime_;
  };
}  // dcmd
#endif
