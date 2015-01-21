#ifndef __DCMD_MACRO_H__
#define __DCMD_MACRO_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxGlobalMacro.h>
#include <CwxStl.h>
#include <CwxStlFunc.h>
#include <CwxType.h>
#include "dcmd_cmn.pb.h"
#include "dcmd_agent.pb.h"
#include "dcmd_ui.pb.h"

CWINUX_USING_NAMESPACE

namespace dcmd {
// 操作指令的参数的前缀
const char* const kAgentOprArgPrex = "DCMD_OPR_";
// center的task type 文件的前缀
const char* const kTaskTypeFilePrex = "dcmd_task_";
// center的task type 文件的后缀
const char* const kTaskTypeFileSuffix = ".script";
// center的opr cmd文件的前缀
const char* const kOprCmdFilePrex = "dcmd_opr_";
// center的opr cmd文件的后缀
const char* const kOprCmdFileSuffix = ".script";
// center的cron的文件前缀
const char* const kCronFilePrex = "dcmd_cron_";
// center的cron的文件后缀
const char* const kCronFileSuffix = ".script";
// center的monitor的文件前缀
const char* const kMonitorFilePrex = "dcmd_monitor_";
// center的monitor的文件后缀
const char* const kMonitorFileSuffix = ".script";
// task result的重发时间
const uint32_t kSubtaskResultResendSecond = 20;
// 操作指令的缺省超时时间
const uint32_t kDefOprCmdTimeoutSecond = 30;
// 操作指令的最小超时时间
const uint32_t kMinOprCmdTimeoutSecond = 2;
// 操作指令最大的超时时间
const uint32_t kMaxOprCmdTimeoutSecond = 3600;
// opr开始排队的缺省门限
const uint32_t kDefOprOverflowThreshold = 250;
// opr overflow的最小门限
const uint32_t kMinOprOverflowThreshold = 1;
// opr overflow的最大门限
const uint32_t KMaxOprOverflowThreshold = 1000;
// 缺省的设备ip表的刷新间隔
const uint32_t kDefIpTableRefreshSecond = 300;
// 最大的设备ip表的刷新间隔
const uint32_t kMaxIpTableRefreshSecond = 1800;
// 最小的设备ip表的刷新间隔
const uint32_t kMinIpTableRefreshSecond = 30;
// 操作指令的最小优先级
const uint32_t kMinOprCmdPriority = 1;
// 操作指令的最大优先级
const uint32_t kMaxOprCmdPriority = 10;
// 缺省的illegal agent的block的时间
const uint32_t kDefIlegalAgentBlockSecond = 180;
// 最小的illegal agnet的block的时间
const uint32_t kMinIllegalAgentBlockSecond = 5;
// 最大的illegal agent的block的时间
const uint32_t kMaxIllegalAgentBlockSecond = 1800;
// 缺省的请求超时时间
const uint32_t kDefReqTimeoutSecond = 10;
// 最小的请求超时时间
const uint32_t kMinReqTimeoutSecond = 1;
// 最大的请求超时时间
const uint32_t kMaxReqTimeoutSecond = 600;
// 缺省的数据包的最大大小
const uint32_t kDefMaxPackageMSize = 4;
// 最小的数据包的最大大小
const uint32_t kMinMaxPackageMSize = 1;
// 最大的数据包的最大大小
const uint32_t kMaxMaxPackageMSize = 16;
// agent ip的最大数量
const uint32_t kAgentMaxIpNum = 32;
// agent ip的最大长度,支持ip V6
const uint32_t kIpMaxLen =  40;
// 缺省的心跳时间
const uint32_t kDefHeatbeatSecond = 10;
// 最大的心跳时间
const uint32_t kMaxHeatbeatSecond = 600;
// 最小的心跳时间
const uint32_t kMinHeatbeatSecond = 1;
// 任务指令执行的stdout、stderr的输出日志保存时间
const uint32_t kTaskCmdOutputExpireDay = 7;
// Center的master切换时间间隔
const uint32_t kCenterMasterSwitchTimeoutSecond = 10;
// Center的master检查时间间隔
const uint32_t kCenterMasterCheckSecond = 1;
// 缺省的日志文件数量
const uint32_t kDefLogFileNum = 7;
// 最小的日志文件数量
const uint32_t kMinLogFileNum = 3;
// 最大的日志文件数量
const uint32_t kMaxLogFileNum = 100;
// 缺省的日志文件大小
const uint32_t kDefLogFileMSize = 10;
// 最小的日志文件大小
const uint32_t kMinLogFileMSize = 1;
// 最大的日志文件大小
const uint32_t kMaxLogFileMSize = 100;
// 2k的buf空间
const uint32_t kDcmd2kBufLen = 2048;
// 字符型的【;】分割符
const char  kItemSplitChar = ';';
// 字符串型的【;】分隔符
const char* const kItemSplitStr = ";";
// 字符型的换行符
const char  kLineSplitChar = '\n';
// 字符串型的换行符
const char* const kLineSplitStr = "\n";
// 任务的执行结果输出目录
const char* const kAgentTaskCmdOutputSubPath = "task_output";
// 操作的执行结果输出目录
const char* const kAgentOprCmdOutputSubPath = "opr_output";
// 任务的事务结果目录
const char* const kDoingTaskPath = "doing_task";
// 任务的script存储目录
const char* const kCenterTaskScriptSubPath = "task_script";
// 操作的script存储目录
const char* const kCenterOprScriptSubPath = "opr_script";
// agent的subtask结果的state key
const char* const kAgentTaskResultKeyState = "state";
// agent的subtask结果的process key
const char* const kAgentTaskResultKeyProcess = "process";
// agent的subtask结果的err key
const char* const kAgentTaskResultKeyErr = "err";
// agent的subtask结果的state的success
const char* const kAgentTaskResultKeyStateSuccess = "success";
// agent的cancel cmd的task_cmd的名字
const char* const kDcmdSysCmdCancel = "cancel";
}  // dcmd
#endif


