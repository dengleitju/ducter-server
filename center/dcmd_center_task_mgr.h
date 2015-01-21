#ifndef __DCMD_CENTER_TASK_MGR__
#define __DCMD_CENTER_TASK_MGR__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include "dcmd_center_def.h"
#include "dcmd_center_h4_agent_task.h"
#include "dcmd_mysql.h"
#include "dcmd_xml_parse.h"
#include "dcmd_center_task_mgr.h"

namespace dcmd {
class DcmdCenterApp;

// 任务管理类，此对象多线程不安全
// 除了GetAgentTaskProcess外，其他api全部由任务线程调用。
// 只有GetAgentTaskProcess/SetAgentTaskProcess/DelAgentsTaskProcess三个api是多线程安全的.
class DcmdCenterTaskMgr{
 public:
  DcmdCenterTaskMgr(DcmdCenterApp* app);
  ~DcmdCenterTaskMgr();
 public:    
  // 启动命令处理，若返回false是数据库操作失败，需要执行stop
  bool Start(DcmdTss* tss);
  // 停止命令处理，若返回false是数据库操作失败
  void Stop(DcmdTss* tss);
  // 接收新指令。false：操作数据库错误。true：数据库没有错误
  bool ReceiveCmd(DcmdTss* tss,
    dcmd_api::UiTaskCmd const& cmd,
    uint32_t  conn_id,
    uint32_t  msg_taskid);
  // agnet的master通知回复通知处理函数，若返回false是数据库操作失败
  bool ReceiveAgentMasterReply(DcmdTss* tss, ///<tss对象
    string const& agent_ip,
    list<string> const& agent_cmds
    );
  // agent连接关闭通知，若返回false是数据库操作失败
  bool ReceiveAgentClosed(DcmdTss* tss, string const& agent_ip);
  // ui的连接关闭通知，此会关闭watch
  bool ReceiveUiClosed(DcmdTss* tss, CWX_UINT32 conn_id);
  // 收到agent的命令确认消息处理，若返回false是数据库操作失败
  bool ReceiveAgentSubtaskConfirm(DcmdTss* tss,
    string const& agent_ip, string cmd_id);
  // 收到agent的命令处理结果，若返回false是数据库操作失败
  bool ReceiveAgentSubtaskResult(DcmdTss* tss,
    uint32_t msg_taskid, dcmd_api::AgentTaskResult const& result,
    uint32_t conn_id
    );
  // 设置agent上的任务处理进度
  void SetAgentTaskProcess(string const& subtask_id,
    char const* process);
  // 获取agent上的任务处理进度, false表示不存在
  bool GetAgentsTaskProcess(string const& subtask_id, string& process);
  // 对所有的任务进行调度，若返回false，是数据库操作失败。
  bool Schedule(DcmdTss* tss);
  // 遍历subTask是否超时
  void CheckSubTaskState(DcmdTss* tss);
  // 是否已经启动
  inline bool IsStart() const { return is_start_; }
 private:
  // 清空对象
  void Reset();
  // 启动任务
  dcmd_api::DcmdState TaskCmdStartTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 暂停任务
  dcmd_api::DcmdState TaskCmdPauseTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 继续任务
  dcmd_api::DcmdState TaskCmdResumeTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 重试任务
  dcmd_api::DcmdState TaskCmdRetryTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 完成任务
  dcmd_api::DcmdState TaskCmdFinishTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 添加新任务节点
  dcmd_api::DcmdState TaskCmdAddTaskNode(DcmdTss* tss, uint32_t task_id,
    char const* svr_pool, char const* ip, uint32_t uid);
  // 删除任务的已有节点
  dcmd_api::DcmdState TaskCmdDelTaskNode(DcmdTss* tss, uint32_t task_id,
    uint64_t subtask_id, uint32_t uid);
  // cancel具体subtask的执行
  dcmd_api::DcmdState TaskCmdCancelSubtask(DcmdTss* tss, uint64_t subtask_id,
    uint32_t uid);
  // cancel一个服务池的所有任务的执行
  dcmd_api::DcmdState TaskCmdCancelSvrPoolSubtask(DcmdTss* tss, uint32_t task_id,
    char const* serivce_pool, char const* agent_ip, uint32_t uid);
  // 执行具体subtask的执行
  dcmd_api::DcmdState TaskCmdExecSubtask(DcmdTss* tss, uint64_t subtask_id,
    uint32_t uid, DcmdCenterCmd** cmd);
  // 重做整个任务
  dcmd_api::DcmdState TaskCmdRedoTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 重做一个服务池子
  dcmd_api::DcmdState TaskCmdRedoSvrPool(DcmdTss* tss, uint32_t task_id,
    char const* svr_pool, uint32_t uid);
  // 重做一个subtask
  dcmd_api::DcmdState TaskCmdRedoSubtask(DcmdTss* tss, uint64_t subtask_id,
    uint32_t uid);
  // ignore某个subtask的结果
  dcmd_api::DcmdState TaskCmdIgnoreSubtask(DcmdTss* tss, uint64_t subtask_id,
    uint32_t uid);
  // 冻结任务的执行
  dcmd_api::DcmdState TaskCmdFreezeTask(DcmdTss* tss,  uint32_t task_id,
   uint32_t uid);
  // 解除对一个任务的冻结
  dcmd_api::DcmdState TaskCmdUnfreezeTask(DcmdTss* tss, uint32_t task_id,
    uint32_t uid);
  // 通知任务信息改变
  dcmd_api::DcmdState TaskCmdUpdateTask(DcmdTss* tss, uint32_t task_id,
    uint32_t con_rate, uint32_t timeout, bool is_auto,
    uint32_t uid);
  // 加载所有的数据
  bool LoadAllDataFromDb(DcmdTss* tss);
  // 从数据库中获取新task
  bool LoadNewTask(DcmdTss* tss, bool is_first);
  // 从数据库中获取新的subtask
  bool LoadNewSubtask(DcmdTss* tss);
  // 从数据库中获取指定的subtask。-2：数据库错误，-1：失败，0：不存在，1：成功
  int LoadSubtaskFromDb(DcmdTss* tss, uint64_t subtask_id);
  // 初始化时，从数据库加载command
  bool LoadAllCmd(DcmdTss* tss);
  // 加载任务的service pool
  bool LoadTaskSvrPool(DcmdTss* tss, DcmdCenterTask* task);
  // 分析任务的信息。返回值，true：成功；false：db操作失败
  bool AnalizeTask(DcmdTss* tss, DcmdCenterTask* task);
  // 读取task cmd的内容
  bool ReadTaskCmdContent(DcmdTss* tss, char const* task_cmd, string& content);
  // 获取task cmd的数据库md5签名。返回值，1：成功；0：不存在；-1：失败
  int FetchTaskCmdInfoFromDb(DcmdTss* tss, char const* task_cmd, string& md5);
  // 调度指定任务的指令，若返回false是数据库操作失败
  bool Schedule(DcmdTss* tss, DcmdCenterTask* task);
  // 子任务完成处理操作，返回对应的task。若返回false是数据库操作失败
  bool FinishTaskCmd(DcmdTss* tss, dcmd_api::AgentTaskResult const& result, DcmdCenterTask*& task );
  // 设置发送的task cmd命令
  void FillTaskCmd(dcmd_api::AgentTaskCmd& cmd, uint64_t cmd_id,
    DcmdCenterSubtask const& subtask);
 private:
   // 根据task id从map中获取task对象
   inline DcmdCenterTask* GetTask(uint32_t task_id);
   // 根据subtask id从map中获取subtask
   inline DcmdCenterSubtask* GetSubTask(uint64_t subtak_id);
   // 获取agent
   inline DcmdCenterAgent* GetAgent(string const& agent_ip);
   // 更新任务无效状态
   inline bool UpdateTaskValid(DcmdTss* tss, bool is_commit, 
     uint32_t task_id, bool is_valid, char const* err_msg);
   // 更新cluster任务的当前执行到的order
   inline bool UpdateTaskState(DcmdTss* tss, bool is_commit,
     uint32_t task_id, uint8_t state);
   // 更新子任务的状态
   inline bool UpdateSubtaskState(DcmdTss* tss, bool is_commit,
     uint64_t subtask_id, uint8_t state, char const* err_msg);
   // 更新子任务的信息
   inline bool UpdateSubtaskInfo(DcmdTss* tss, uint64_t subtask_id,
     bool is_commit, uint32_t* state, bool* is_skip,
     bool is_start_time, bool is_finish_time, char const* err_msg, 
     char const* process);
   // 更新命令的状态
   inline bool UpdateCmdState(DcmdTss* tss, bool is_commit,
     uint64_t cmd_id, uint8_t state, char const* err_msg);
   // 更新任务的信息
   inline bool UpdateTaskInfo(DcmdTss* tss, bool is_commit,uint32_t task_id,
     uint32_t con_rate, uint32_t timeout, bool is_auto,
     uint32_t uid);
   // 创建cmd, 0表示失败
   inline uint64_t InsertCommand(DcmdTss* tss, bool is_commit, uint32_t uid,
     uint32_t task_id, uint64_t subtask_id, char const* svr_pool,
     uint32_t svr_pool_id, char const* service, char const* ip,
     uint8_t cmt_type, uint8_t state, char const* err_msg);
   // 执行sql
   inline bool ExecSql(DcmdTss* tss, bool is_commit);
   // 将任务的相关信息从内存中删除
   inline void RemoveTaskFromMem(DcmdCenterTask* task);
   // 计算任务及svr_pool的信息
   inline bool CalcTaskStatsInfo(DcmdTss* tss, bool is_commit, DcmdCenterTask* task); 
   // 删除指定的cmd
   void RemoveCmd(DcmdCenterCmd* cmd);
   // 设置发送的cancel命令
   inline void FillCtrlCmd(dcmd_api::AgentTaskCmd& cmd, uint64_t cmd_id,
     dcmd_api::CmdType cmd_type, string const& agent_ip,
     string const& svr_name, string const& svr_pool, DcmdCenterSubtask* subtask
     );
 private:
  // app对象
  DcmdCenterApp*                               app_;
  // 保护all_subtasks_的访问，并发访问process
  CwxMutexLock                                 lock_;  
  /****** 一下的变量全部是多线程不安全的  *****/
  // 是否已经启动
  bool                                         is_start_;
  // mysql 的句柄
  Mysql*                                       mysql_;
  // 当前有待处理subtask的agent
  map<string, DcmdCenterAgent*>                agents_;
  // 当前所有的task
  map<uint32_t, DcmdCenterTask*>               all_tasks_;
  // 当前的所有subtask。subtask的状态、ignore必须通过svr-pool对象修改
  map<uint64_t, DcmdCenterSubtask*>            all_subtasks_;
  // 当前所有的等待回复的命令
  map<uint64_t, DcmdCenterCmd*>                waiting_cmds_;
  // 下一个的task的id
  uint32_t                                     next_task_id_;
  // 下一个subtask id
  uint64_t                                     next_subtask_id_;
  // 下一个command的id
  uint64_t                                     next_cmd_id_;
  // watch的管理器对象
  //DcmdCenterWatchMgr*                          watches_;
};
}  // dcmd
#include "dcmd_center_task_mgr.inl"
#endif
