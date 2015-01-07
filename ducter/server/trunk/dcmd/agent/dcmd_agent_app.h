#ifndef __DCMD_AGENT_APP_H__
#define __DCMD_AGENT_APP_H__

/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/

#include <CwxAppFramework.h>
#include "dcmd_macro.h"
#include "dcmd_tss.h"
#include "dcmd_agent_config.h"
#include "dcmd_agent_def.h"
#include "dcmd_process.h"

namespace dcmd {
const char* const kDcmdAgentVersion = "1.0.2";
const char* const kDcmdAgentModifyDate = "2015-01-4 13:30:08";
// agent的app对象
class DcmdAgentApp : public CwxAppFramework{
 public:
  enum{
    // 监控的服务类型
	  SVR_TYPE_CENTER = CwxAppFramework::SVR_TYPE_USER_START
   };

  DcmdAgentApp();
  virtual ~DcmdAgentApp(); 
 public:
   virtual int init(int argc, char** argv);
  // 时钟响应函数
  virtual void onTime(CwxTimeValue const& current);
  // signal响应函数
  virtual void onSignal(int signum);
  // 连接建立通知
  virtual int onConnCreated(CwxAppHandler4Msg& conn,
    bool& bSuspendConn, bool& bSuspendListen);
  // 连接关闭
  virtual int onConnClosed(CwxAppHandler4Msg& conn);
  // 收到消息的响应函数
  virtual int onRecvMsg(CwxMsgBlock* msg, CwxAppHandler4Msg& conn,
    CwxMsgHead const& header, bool& bSuspendConn);

 public:
  // 计算机的时钟是否回调
  inline bool IsClockBack(uint32_t& last_time, uint32_t now) const {
    if (last_time > now + 1){
      last_time = now;
      return true;
    }
    last_time = now;
    return false;
  }
 protected:
  // 重载运行环境设置API
  virtual int initRunEnv();
  virtual void destroy();
 private:
  // 清空对象，释放空间
	void Reset();
  // 初始化目录
  bool InitPath(string const& path, bool is_clean_path);
  // 获取任务的临时目录
  inline string& GetTaskScriptPath(string& path) {
    path = config_.conf().work_home_ + kCenterTaskScriptSubPath;
    return path;
  }
  // 获取正在做任务的目录
  inline string& GetDoingTaskPath(string& path) {
    path = config_.conf().work_home_ + kDoingTaskPath;
    return path;
  }
  // 获取task执行结果的目录
  inline string& GetTaskOutputPath(string& path) {
    path = config_.conf().work_home_ + kAgentTaskCmdOutputSubPath;
    return path;
  }
  // 获取正在执行的subtask的文件
  inline string& GetDoingTaskFile(string& file, string const& subtask_id) {
    GetDoingTaskPath(file);
    file += string("/") + subtask_id;
    return file;
  }
  // 获取任务的执行的stdout、stderr结果的输出文件
  inline string& GetTaskOutputFile(string& file, string const& subtask_id) {
    GetTaskOutputPath(file);
    file += string("/sub_task_") + subtask_id + ".out";
    return file;
  }
  // 获取Task执行的结果文件
  inline string& GetTaskResultFile(string const& subtask_id, string& file) {
    GetTaskOutputPath(file);
    file += string("/result_") + subtask_id + string(".result");
    return file;
  }
  // 获取任务执行的script文件
  inline string& GetTaskRunScriptFile(string const& subtask_id, string& file)
  {
    GetTaskScriptPath(file);
    file += string("/dcmd_task_") + subtask_id + ".script";
    return file;        
  }
  // 获取任务执行的脚本shell文件
  inline string& GetTaskRunScriptShellFile(string const& subtask_id, string& file)
  {
    GetTaskRunScriptFile(subtask_id, file);
    file += ".sh";
    return file;        
  }
  // 获取操作的脚本目录
  inline string& GetOprScriptPath(string& path) {
    path = config_.conf().work_home_ + kCenterOprScriptSubPath;
    return path;
  }
  // 获取操作的输出目录
  inline string& GetOprOutputPath(string& path) {
    path = config_.conf().work_home_ + kAgentOprCmdOutputSubPath;
    return path;
  }
  // 获取操作指令执行的script文件
  inline string& GetOprRunScriptFile(string const& script_name, uint64_t agent_opr_id,
    string& file)
  {
    char buf[64];
    CwxCommon::toString(agent_opr_id, buf, 10);
    GetOprScriptPath(file);
    file += string("/") + script_name + string("_") + buf + string(".script");
    return file;
  }
  // 获取操作的script shell文件
  inline string& GetOprRunScriptShellFile(string const& script_name, uint64_t agent_opr_id,
    string& file)
  {
    GetOprRunScriptFile(script_name, agent_opr_id, file);
    file += string(".sh");
    return file;
  }
  // 获取操作指令的stdout、stderr输出文件名
  inline string& GetOprOuputFile(string const& script_name, uint64_t agent_opr_id,
    string& file) {
    char buf[64];
    CwxCommon::toString(agent_opr_id, buf, 10);
    GetOprOutputPath(file);
    file += string("/") + script_name + string("_") + buf + ".out";
    return file;
  }
  // 检查超时的任务指令output
  void CheckExpiredTaskOutput(uint32_t now);
  // 检查任务、操作指令
  void CheckTaskAndOprCmd();
  // 检查新收到的命令
  void CheckNewReceivedCmd();
  // 检查task的运行状态
  void CheckTasks();
  // 检查需要回复的命令
  void CheckTaskWaitingToReply();
  // 调度opr 命令
  void DispatchOprCmd();
  // 心跳检测
	void CheckHeatbeat();
  // 检查app的task指令
  void CheckSvrTask(AgentSvrPoolObj* svr_pool_obj);
  // 执行操作整理。true：已经完成；false：正在执行
  bool CheckOprCmd(AgentOprCmd* opr_cmd, bool is_cancel=false);
  // 检查center对于subtask result的回复
  void CheckSubtaskResultReply();
	// 处理收到的消息。 -1：失败并关闭连接；0：成功
	int RecvMsg(CwxMsgBlock*& msg);
  // 检查正在运行的subtask
  void CheckRuningSubTask(AgentSvrPoolObj* svr_pool_obj, bool is_cancel=false);
  // 处理控制指令
  void ExecCtrlTaskCmdForCancelAll(AgentSvrPoolObj* svr_pool_obj, AgentTaskCmd* cmd);
  // 处理控制指令
  void ExecCtrlTaskCmdForCancelSubTask(AgentSvrPoolObj* svr_pool_obj, AgentTaskCmd* cmd);
  // 处理控制指令
  void ExecCancelSubTask(AgentSvrPoolObj* svr_pool_obj, string const& subtask_id);
  // 准备subtask命令运行的环境
  bool PrepareSubtaskRunEnv(AgentTaskCmd* cmd, string& err_msg);
  // 基于subtask形成task result
  void FillTaskResult(AgentTaskCmd const& cmd, AgentTaskResult& result,
    string const& process, bool success, string const& err_msg);
  // 执行subtask指令。
  bool ExecSubTaskCmd(AgentTaskCmd* cmd, string& err_msg, DcmdProcess*& process);
  // 准备shell命令，形成shell文件。true：成功；false：失败
  bool PrepareOprRunEnv(AgentOprCmd* opr_cmd, string& err_msg);
  // 执行操作的命令。 true：成功；false：失败
  bool ExecOprCmd(AgentOprCmd* opr_cmd, string& err_msg, DcmdProcess*& process);
  // 回复操作指令的处理结果，0：成功；-1：失败
  int ReplyOprCmd(AgentCenter* center,
    uint32_t msg_task_id,
    bool  is_success,
    int   status,
    char const* result,
    char const* err_msg);
  // 处理report的结果。 -1：失败并关闭连接；0：成功
  int ReportReply(CwxMsgBlock*& msg, AgentCenter* center);
	// master发生改变。 -1：失败并关闭连接；0：成功
	int MasterChanged(CwxMsgBlock*& msg, AgentCenter* center);
	// 处理收到的任务。 -1：失败并关闭连接；0：成功
	int SubTaskCmdRecieved(CwxMsgBlock*& msg, AgentCenter* center);
	// 处理任务结果的回复。 -1：失败并关闭连接；0：成功
	int SubTaskResultReply(CwxMsgBlock*& msg, AgentCenter* center);
  // 处理收到的操作指令。 -1：失败并关闭连接；0：成功
  int OprCmdRecieved(CwxMsgBlock*& msg, AgentCenter* center);
  // 处理收到的获取任务执行输出的消息。 -1：失败并关闭连接；0：成功
  int FetchTaskOutputResultRecieved(CwxMsgBlock*& msg, AgentCenter* center);
  // 处理收到的获取运行task的命令。 -1：失败并关闭连接；0：成功
  int GetRunTaskRecieved(CwxMsgBlock*& msg, AgentCenter* center);
  // 处理收到的获取运行操作指令。 -1：失败并关闭连接；0：成功
  int GetRunOprRecieved(CwxMsgBlock*& msg, AgentCenter* center);
  // 检查service当前命令的进度信息
  void CheckSubTaskProcess(AgentSvrPoolObj* svr_pool_obj);
  // 获取app任务执行的输出文件内容
  void LoadSubTaskResult(string const& subtask_id, string& out_process, bool& is_success, string& err_msg, bool& is_finish,
    bool is_process_only);
  // 获取一个service的运行任务信息
  void DumpRuningAppSubTask(AgentSvrPoolObj* svr_pool_obj, string& dump);
  // 获取package的buf，返回NULL表示失败
  inline char* GetBuf(uint32_t size){
    if (data_buf_len_ < size){
      delete [] data_buf_;
      data_buf_ = new char[size];
      data_buf_len_ = size;
    }
    return data_buf_;
  }
  ///获取满足条件的最后一个key/value
  bool findLastKey(list< pair<string, string> > const & values,
      string const& name, pair<string, string>& item) {
    list<pair<string, string> >::const_reverse_iterator  iter = values.rbegin();
    while (iter != values.rend()) {
      if (strcmp(iter->first.c_str(), name.c_str()) == 0) {
        item = (*iter);
        return true;
      }
      iter++;
    }
    return false;
  }
  //获取正在做的task
  bool loadDoingTasks();
  //获取正在做的task的信息
  bool loadDoingTask(string const& subtask_id, AgentDoingTaskInfo& task_info);
  //删除正在做的task的信息
  bool removeDoingTask(string const& subtask_id);
  //保存正在做的task的信息
  bool saveDoingTask(AgentDoingTaskInfo const& task_info);


 private:
  // 控制中心的map
  map<uint32_t, AgentCenter*>                 center_map_;
  // 当前的master控制中心
  AgentCenter*                                master_;
  // 配置文件
  DcmdAgentConfig                             config_;
  // svr pool的指令，key为app_name_svr_name_svr_pool_name
  map<string, AgentSvrPoolObj*>               svr_pool_map_;
  // 等待回复的命令结果，key为cmd_id
  map<uint64_t, AgentTaskResult*>             wait_reply_result_map_;
  // 等待发送的处理结果，此是由于与center失去联系造成的,key为cmd_id。
  map<uint64_t, AgentTaskResult*>             wait_send_result_map_;
  // 刚刚收到的subtask命令
  list<AgentTaskCmd*>                         recieved_subtasks_;
  // 所有待处理的subtask指令列表
  map<uint64_t, AgentTaskCmd*>               subtask_map_;
  // 操作命令的map。
  map<uint64_t, AgentOprCmd*>                opr_cmd_map_;
  // 操作数量的overflow 门限
  uint32_t                                   opr_overflow_threshold_;
  // 下一个操作指令的id
  uint64_t                                   next_opr_cmd_id_;
  // agent服务器的ip列表，不包括127.0
  list<string>                               agent_ips_;
  // 主机的名字
  string                                     host_name_;
  // protobuf parse/serialize的字符串
  string                                     proto_str_;
  // 数据buf
  char*                                      data_buf_;
  // 数据buf的空间大小
  uint32_t                                   data_buf_len_;
   // 错误buf
  char                                       err_2k_[kDcmd2kBufLen];
};
}  // dcmd
#endif

