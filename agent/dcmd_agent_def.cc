#include "dcmd_agent_def.h"

namespace dcmd {
AgentCenter::AgentCenter(){
  host_id_ = 0;
  host_port_ = 0;
  conn_id_ = 0;
  last_heatbeat_time_ = 0;
  opr_overflow_threshold_ = kDefOprOverflowThreshold;
  heatbeat_internal_ = kDefHeatbeatSecond;
  max_package_size_ = kDefMaxPackageMSize;
  is_connected_ = false;
  is_auth_ = false;
  is_auth_failed_ = false;
}
void AgentDoingTaskInfo::dump(string& result) const {
  char* buf = new char[2048];
  CwxCommon::snprintf(buf, 2047, "msg_task_id=%u\ntask_id=%s\nsubtask_id=%s\ncmd_id=%s\nsvr_pool=%s\ntask_cmd=%s\ntask_result_file=%s\n",
    msg_task_id_, task_id_.c_str(), subtask_id_.c_str(), cmd_id_.c_str(), svr_pool_.c_str(), task_cmd_.c_str(), task_result_file_.c_str());
  result = buf;
  delete buf;
}
bool AgentDoingTaskInfo::load(string const& result) {
  list<pair<string,string> > kvs;
  pair<string,string> kv;
  CwxCommon::split(result, kvs, '\n');
  if (!CwxCommon::findKey(kvs, "msg_task_id", kv)) return false;
  msg_task_id_=strtoul(kv.second.c_str(), NULL, 10);
  if (!CwxCommon::findKey(kvs, "task_id", kv)) return false;
  task_id_ = CwxCommon::trim(kv.second);
  if (!CwxCommon::findKey(kvs, "subtask_id", kv)) return false;
  subtask_id_ = CwxCommon::trim(kv.second);
  if (!CwxCommon::findKey(kvs, "cmd_id", kv)) return false;
  cmd_id_ = CwxCommon::trim(kv.second);
  if (!CwxCommon::findKey(kvs, "svr_pool", kv)) return false;
  svr_pool_ = CwxCommon::trim(kv.second);
  if (!CwxCommon::findKey(kvs, "task_cmd", kv)) return false;
  task_cmd_ = CwxCommon::trim(kv.second);
  if (!CwxCommon::findKey(kvs, "task_result_file", kv)) return false;
  task_result_file_ = CwxCommon::trim(kv.second);
  return true;
}

}

