#ifndef __DCMD_CENTER_OPR_CACHE_H__
#define __DCMD_CENTER_OPR_CACHE_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxAppConnInfo.h>
#include <CwxMutexLock.h>
#include <CwxLockGuard.h>
#include <CwxMsgBlock.h>
#include "dcmd_center_def.h"

namespace dcmd {
// 操作对象，来自opr_cmd_exec表
class DcmdCenterOprCmd{
 public:
  enum{
    // 不重复
    DCMD_OPR_CMD_NO_REPEAT = 0,
    // 可重复执行，而且不记录history
    DCMD_OPR_CMD_REPEAT_WITHOUT_HISTORY = 1,
    // 可重复执行，而且记录历史
    DCMD_OPR_CMD_REPEAT_HISTORY = 2,
    // REPEAT类型的最大值
    DCMD_OPR_CMD_REPEAT_MAX = 2
  };

 public:
  DcmdCenterOprCmd() {
    opr_cmd_id_ = 0;
    opr_timeout_ = 0;
    repeat_type_ = DCMD_OPR_CMD_NO_REPEAT;
    is_arg_mutable_ = false;
    is_agent_mutable_ = false;
    cache_time_ = 0;
    expire_time_ = 0;
  }
  DcmdCenterOprCmd& operator=(DcmdCenterOprCmd const& item) {
    if (this == &item) return *this;
    opr_cmd_id_ = item.opr_cmd_id_;
    opr_name_ = item.opr_name_;
    opr_file_ = item.opr_file_;
    opr_file_md5_ = item.opr_file_md5_;
    opr_run_user_ = item.opr_run_user_; 
    opr_timeout_ = item.opr_timeout_;
    agents_ = item.agents_;
    opr_args_ = item.opr_args_;
    opr_args_map_ = item.opr_args_map_;
    opr_script_content_ = item.opr_script_content_;
    repeat_type_ = item.repeat_type_;
    is_arg_mutable_ = item.is_arg_mutable_;
    is_agent_mutable_ = item.is_agent_mutable_;
    mutable_args_ = item.mutable_args_;
    cache_time_ = item.cache_time_;
    expire_time_ = item.expire_time_;
    return *this;
  }
  DcmdCenterOprCmd(DcmdCenterOprCmd const& item) {
    opr_cmd_id_ = item.opr_cmd_id_;
    opr_name_ = item.opr_name_;
    opr_file_ = item.opr_file_;
    opr_file_md5_ = item.opr_file_md5_;
    opr_run_user_ = item.opr_run_user_; 
    opr_timeout_ = item.opr_timeout_;
    agents_ = item.agents_;
    opr_args_ = item.opr_args_;
    opr_args_map_ = item.opr_args_map_;
    opr_script_content_ = item.opr_script_content_;
    repeat_type_ = item.repeat_type_;
    is_arg_mutable_ = item.is_arg_mutable_;
    is_agent_mutable_ = item.is_agent_mutable_;
    mutable_args_ = item.mutable_args_;
    cache_time_ = item.cache_time_;
    expire_time_ = item.expire_time_;
  }
 public:
  // 操作的命令id
  uint64_t              opr_cmd_id_;
  // opr操作的名字
  string                opr_name_;
  // opr操作的文件名
  string                opr_file_;
  // opr操作文件的md5签名
  string                opr_file_md5_;
  // opr的运行用户
  string                opr_run_user_;
  // opr的执行超时时间
  uint32_t              opr_timeout_;
  // opr操作对应的agent
  set<string>           agents_;
  // 操作的参数
  string                opr_args_;
  // opr对应的参数
  map<string, string>   opr_args_map_;
  // opr的cmd文件名
  string                opr_script_content_;
  // 可重复执行的类型
  uint8_t               repeat_type_;
  // 参数是否是可变的
  bool                  is_arg_mutable_;
  // 是否agent的列表可变
  bool                  is_agent_mutable_;
  // 可变的参数列表
  list<string>          mutable_args_;
  // cache的秒数
  uint32_t              cache_time_;
  // 失效的时间
  uint32_t              expire_time_;
};

// Opr cmd的cache对象
class DcmdCenterOprCache {
 public:
  DcmdCenterOprCache() {
  }
  ~DcmdCenterOprCache() {
    map<string, DcmdCenterOprCmd*>::iterator iter = cmd_cache_.begin();
    while(iter != cmd_cache_.end()){
      delete iter->second;
      ++iter;
    }
  }
 public:
  // 添加opr cmd到cache
  void AddOprCmd(string const& dup_opr_name, DcmdCenterOprCmd const& cmd);
  // 获取opr cmd cache，返回值false表示不存在
  bool GetOprCmd(string const& dup_opr_name, DcmdCenterOprCmd& cmd);
  // 检查超时的opr cmd
  void CheckTimeout(uint32_t now);
 private:
  // 访问互斥锁
  CwxMutexLock                       lock_;
  // 基于命令id的cache
  map<string, DcmdCenterOprCmd*>     cmd_cache_;
};
}  // dcmd
#endif

