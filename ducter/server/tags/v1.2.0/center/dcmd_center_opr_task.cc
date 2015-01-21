#include "dcmd_center_run_opr_task.h"
#include "dcmd_center_agent_mgr.h"
#include <CwxMd5.h>
#include "dcmd_center_app.h"
namespace dcmd {
void DcmdCenterOprTask::noticeTimeout(CwxTss* ) {
  is_reply_timeout_ = true;
  setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
  CWX_DEBUG(("Task is timeout , task_id=%u", getTaskId()));
}
void DcmdCenterOprTask::noticeRecvMsg(CwxMsgBlock*& msg, CwxTss* ThrEnv, bool& ){
  DcmdTss* tss = (DcmdTss*)ThrEnv;
  for (uint16_t i=0; i<agent_num_; i++) {
    if (msg->event().getConnId() == agent_conns_[i].conn_id_) {
      agent_replys_[i].recv_msg_ = msg;
      msg = NULL;
      receive_num_++;
      if (receive_num_ >= agent_num_) setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
      dcmd_api::AgentOprCmdReply reply;
      tss->proto_str_.assign(agent_replys_[i].recv_msg_->rd_ptr(), agent_replys_[i].recv_msg_->length());
      if (!reply.ParseFromString(tss->proto_str_)) {
        CWX_ERROR(("Failed to unpack msg from %s.", agent_conns_[i].agent_ip_.c_str()));
        agent_replys_[i].is_exec_success = false;
        agent_replys_[i].status_ = 0;
        agent_replys_[i].err_msg_ = "Failed to unpack msg";
      } else {
        if (reply.state()==dcmd_api::DCMD_STATE_SUCCESS) {
          agent_replys_[i].is_exec_success = true;
          agent_replys_[i].result_ = reply.result();
        } else {
          agent_replys_[i].is_exec_success = false;
          agent_replys_[i].err_msg_ = reply.err();
        }
        agent_replys_[i].status_ = reply.status();
      }
      return ;
    }
  }
  CWX_ASSERT(0);
}
void DcmdCenterOprTask::noticeFailSendMsg(CwxMsgBlock*& msg, CwxTss* ) {
  for(uint32_t i=0; i<agent_num_; i++) {
    if (agent_conns_[i].conn_id_ == msg->send_ctrl().getConnId()) {
      receive_num_++;
      agent_replys_[i].is_send_failed_ = true;
      if (receive_num_ >= agent_num_)
        setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
      return;
    }
  }
  CWX_ASSERT(0);
}
void DcmdCenterOprTask::noticeEndSendMsg(CwxMsgBlock*& , CwxTss* , bool& ){
}
void DcmdCenterOprTask::noticeConnClosed(CWX_UINT32 , CWX_UINT32 , CWX_UINT32 uiConnId, CwxTss*){
  for (uint16_t i=0; i<agent_num_; i++) {
    if (uiConnId == agent_conns_[i].conn_id_) {
      agent_replys_[i].recv_msg_ = NULL;
      agent_replys_[i].is_send_failed_ = true;
      receive_num_++;
      if (receive_num_ >= agent_num_) setTaskState(CwxTaskBoardTask::TASK_STATE_FINISH);
      return;
    }
  }
}
bool DcmdCenterOprTask::FetchOprCmd(DcmdTss* tss) {
  ///从db中获取
  Mysql* my = app_->GetAdminMysql();
  if (!app_->CheckMysql(my)){
    CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to connect mysql, error=%s", my->getErrMsg());
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    return false;
  }
  my->commit();
  //从mysql获取opr指令的信息
  CwxCommon::snprintf(tss->sql_,
    DcmdTss::kMaxSqlBufSize,
    "select a.ui_name, a.opr_cmd, a.run_user,a.script_md5,"\
    "b.timeout, b.ip, b.arg "\
    "from  dcmd_opr_cmd as a, dcmd_opr_cmd_exec as b "\
    "where b.exec_id =%s and a.opr_cmd = b.opr_cmd",
    CwxCommon::toString(opr_cmd_id_, tss->m_szBuf2K, 10));
  if (!my->query(tss->sql_)){
    CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to fetch opr cmd from mysql, Sql:%s error=%s", tss->sql_, my->getErrMsg());
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    my->disconnect();
    return false;
  }
  ///获取sql的结果
  if (1 != my->next()) {
    CwxCommon::snprintf(tss->m_szBuf2K, 2047,
      "Command[%s] doesn't exist in table[%s] table",
      CwxCommon::toString(opr_cmd_id_, tss->sql_, 10),
      "dcmd_opr_cmd_exec");
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    ///释放结果集
    my->freeResult();
    return false;
  }
  bool bNull = false;
  opr_cmd_.opr_cmd_id_ = opr_cmd_id_;
  // 获取ui name
  opr_cmd_.opr_name_ = my->fetch(0, bNull);
  // 获取opr file
  opr_cmd_.opr_file_ = my->fetch(1, bNull);
  // 获取run user
  opr_cmd_.opr_run_user_ = my->fetch(2, bNull);
  // 获取opr file的md5
  opr_cmd_.opr_file_md5_ = my->fetch(3, bNull);
  opr_cmd_.is_agent_mutable_ = false;
  // 获取opr的timeout
  opr_cmd_.opr_timeout_ = strtoul(my->fetch(4, bNull), NULL, 10);
  if (opr_cmd_.opr_timeout_ < kMinOprCmdTimeoutSecond) opr_cmd_.opr_timeout_ = kMinOprCmdTimeoutSecond;
  if (opr_cmd_.opr_timeout_ > kMaxOprCmdTimeoutSecond) opr_cmd_.opr_timeout_ = kMaxOprCmdTimeoutSecond;
  // 获取ip
  list<string> ips;
  string agent_ip;
  CwxCommon::split(my->fetch(5, bNull), ips, kItemSplitChar);
  list<string>::iterator iter = ips.begin();
  while(iter != ips.end()) {
    agent_ip = *iter;
    CwxCommon::trim(agent_ip);
    if (agent_ip.length()) {
      opr_cmd_.agents_.insert(agent_ip);
    }
    ++iter;
  }
  // 获取arg
  opr_cmd_.opr_args_ = my->fetch(6, bNull);
  // 释放结果集
  my->freeResult();
  // 获取脚本、检查md5
  string opr_file;
  DcmdCenterConf::opr_cmd_file(app_->config().common().opr_script_path_,
    opr_cmd_.opr_file_, opr_file);
  if (!tss->ReadFile(opr_file.c_str(), opr_cmd_.opr_script_content_, err_msg_)){
    CWX_ERROR((err_msg_.c_str()));
    return false;
  }
  // 计算md5
  string file_md5;
  dcmd_md5(opr_cmd_.opr_script_content_.c_str(), opr_cmd_.opr_script_content_.length(), file_md5);
  if (strcasecmp(opr_cmd_.opr_file_md5_.c_str(), file_md5.c_str()) != 0){
    CwxCommon::snprintf(tss->m_szBuf2K, 2047, "opr-file[%s]'s md5[%s] is not same with table's md5:%s",
      opr_file.c_str(),
      file_md5.c_str(),
      opr_cmd_.opr_file_md5_.c_str());
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    return false;
  }
  // 形成参数
  opr_cmd_.opr_args_map_.clear();
  if (opr_cmd_.opr_args_.length()){
    XmlConfigParser parser;
    if (!parser.parse(opr_cmd_.opr_args_.c_str())){
      CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to parse shell arg for invalid xml, arg:%s", opr_cmd_.opr_args_.c_str());
      CWX_ERROR((tss->m_szBuf2K));
      err_msg_ = tss->m_szBuf2K;
      return false;
    }
    XmlTreeNode const* node = parser.getRoot()->m_pChildHead;
    string strNodeValue;
    while(node){
      if (node->m_pChildHead){
        CwxCommon::snprintf(tss->m_szBuf2K, 2047, "arg[%s] has child, it's invalid.", node->m_szElement);
        CWX_ERROR((tss->m_szBuf2K));
        err_msg_ = tss->m_szBuf2K;
        return false;
        }
      strNodeValue = "";
      list<char*>::const_iterator iter = node->m_listData.begin();
      while(iter != node->m_listData.end()){
        strNodeValue += *iter;
        iter++;
      }
      opr_cmd_.opr_args_map_[string(node->m_szElement)] = strNodeValue;
      node = node->m_next;
    }
  }
  agents_ = opr_cmd_.agents_;
  if (!agents_.size()) {
    err_msg_ = "No host ip";
    return false;
  }
  // 设置参数
  opr_args_ = opr_cmd_.opr_args_map_;
  // 记录操作历史
  CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
    "insert into dcmd_opr_cmd_exec_history(exec_id, opr_cmd_Id, opr_cmd, run_user, timeout, ip, "\
    "arg, utime, ctime, opr_uid) "\
    "select exec_id, opr_cmd_Id, opr_cmd, run_user, timeout, ip, "\
    "arg, now(), ctime, opr_uid from dcmd_opr_cmd_exec \
    where exec_id=%s",
    CwxCommon::toString(opr_cmd_id_, tss->m_szBuf2K, 10));
  if (-1 == my->execute(tss->sql_)){
    CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to exec sql:%s, err=%s",  tss->sql_, my->getErrMsg());
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    my->rollback();
    my->disconnect();
    return false;
  }
  //删除数据
  CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
    "delete from dcmd_opr_cmd_exec where exec_id=%s",
    CwxCommon::toString(opr_cmd_id_, tss->m_szBuf2K, 10));
  if (-1 == my->execute(tss->sql_)){
    CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to delete opr , exec sql:%s",  tss->sql_);
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    my->rollback();
    my->disconnect();
    return false;
  }
  my->commit();
  return true;
}

bool DcmdCenterOprTask::FetchDupOprCmd(DcmdTss* tss) {
  ///从db中获取
  Mysql* my = app_->GetAdminMysql();
  if (!app_->CheckMysql(my)){
    CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to connect mysql, error=%s", my->getErrMsg());
    CWX_ERROR((tss->m_szBuf2K));
    err_msg_ = tss->m_szBuf2K;
    return false;
  }
  my->commit();
  string opr_name = dup_opr_name_;
  dcmd_escape_mysql_string(opr_name);
  // 是否从mysql获取
  if (!app_->GetOprCmdCache()->GetOprCmd(dup_opr_name_, opr_cmd_) || IsScriptChanged(tss)){
    //从mysql获取opr指令的信息
    CwxCommon::snprintf(tss->sql_,
      DcmdTss::kMaxSqlBufSize,
      "select b.repeat_cmd_id, a.opr_cmd, a.run_user,a.script_md5, b.ip_mutable,"\
      "b.timeout, b.ip, b.arg, b.repeat, b.cache_time, b.arg_mutable "\
      "from  dcmd_opr_cmd as a, dcmd_opr_cmd_repeat_exec as b "\
      "where b.repeat_cmd_name ='%s' and a.opr_cmd = b.opr_cmd",
      opr_name.c_str());
    if (!my->query(tss->sql_)){
      CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to fetch opr cmd from mysql, Sql:%s error=%s", tss->sql_, my->getErrMsg());
      CWX_ERROR((tss->m_szBuf2K));
      err_msg_ = tss->m_szBuf2K;
      my->disconnect();
      return false;
    }
    ///获取sql的结果
    if (1 != my->next()) {
      CwxCommon::snprintf(tss->m_szBuf2K, 2047,
        "Command[%s] doesn't exist in table[%s] table",
        dup_opr_name_.c_str(),
        "dcmd_opr_cmd_repeat_exec");
      CWX_ERROR((tss->m_szBuf2K));
      err_msg_ = tss->m_szBuf2K;
      ///释放结果集
      my->freeResult();
      return false;
    }
    bool bNull = false;
    opr_cmd_.opr_cmd_id_ = strtoull(my->fetch(0, bNull), NULL, 10);
    // 获取ui name
    opr_cmd_.opr_name_ = dup_opr_name_;
    // 获取opr file
    opr_cmd_.opr_file_ = my->fetch(1, bNull);
    // 获取run user
    opr_cmd_.opr_run_user_ = my->fetch(2, bNull);
    // 获取opr file的md5
    opr_cmd_.opr_file_md5_ = my->fetch(3, bNull);
    // 获取 agent是否可变
    opr_cmd_.is_agent_mutable_ = strtoul(my->fetch(4, bNull), NULL, 10);
    ///获取opr的timeout
    opr_cmd_.opr_timeout_ = strtoul(my->fetch(5, bNull), NULL, 10);
    if (opr_cmd_.opr_timeout_ < kMinOprCmdTimeoutSecond) opr_cmd_.opr_timeout_ = kMinOprCmdTimeoutSecond;
    if (opr_cmd_.opr_timeout_ > kMaxOprCmdTimeoutSecond) opr_cmd_.opr_timeout_ = kMaxOprCmdTimeoutSecond;
    ///获取ip
    list<string> ips;
    string agent_ip;
    CwxCommon::split(my->fetch(6, bNull), ips, kItemSplitChar);
    list<string>::iterator iter = ips.begin();
    while(iter != ips.end()) {
      agent_ip = *iter;
      CwxCommon::trim(agent_ip);
      if (agent_ip.length()) {
        opr_cmd_.agents_.insert(agent_ip);
      }
      ++iter;
    }
    ///获取arg
    opr_cmd_.opr_args_ = my->fetch(7, bNull);
    // 获取repeat
    opr_cmd_.repeat_type_ = strtoul(my->fetch(8, bNull), NULL, 10);
    if (opr_cmd_.repeat_type_ > DcmdCenterOprCmd::DCMD_OPR_CMD_REPEAT_MAX)
      opr_cmd_.repeat_type_ = DcmdCenterOprCmd::DCMD_OPR_CMD_REPEAT_HISTORY;
    if (app_->config().common().is_opr_cmd_history_){
      if (opr_cmd_.repeat_type_ == DcmdCenterOprCmd::DCMD_OPR_CMD_REPEAT_WITHOUT_HISTORY)
        opr_cmd_.repeat_type_ = DcmdCenterOprCmd::DCMD_OPR_CMD_REPEAT_HISTORY;
    }
    ///获取cache
    opr_cmd_.cache_time_ = strtoul(my->fetch(9, bNull), NULL, 10);
    opr_cmd_.expire_time_ =opr_cmd_.cache_time_?((uint32_t)time(NULL)) + opr_cmd_.cache_time_:0;
    ///获取arg mutable
    opr_cmd_.is_arg_mutable_ = strtoul(my->fetch(10, bNull), NULL, 10)?true:false;
    if (!app_->config().common().is_opr_cmd_arg_mutable_) opr_cmd_.is_arg_mutable_ = false;
    ///释放结果集
    my->freeResult();
    ///获取脚本、检查md5
    string opr_file;
    DcmdCenterConf::opr_cmd_file(app_->config().common().opr_script_path_,
      opr_cmd_.opr_file_, opr_file);
    if (!tss->ReadFile(opr_file.c_str(), opr_cmd_.opr_script_content_, err_msg_)){
      CWX_ERROR((err_msg_.c_str()));
      return false;
    }
    // 计算md5
    string file_md5;
    dcmd_md5(opr_cmd_.opr_script_content_.c_str(), opr_cmd_.opr_script_content_.length(), file_md5);
    if (strcasecmp(opr_cmd_.opr_file_md5_.c_str(), file_md5.c_str()) != 0){
      CwxCommon::snprintf(tss->m_szBuf2K, 2047, "opr-file[%s]'s md5[%s] is not same with table's md5:%s",
        opr_file.c_str(),
        file_md5.c_str(),
        opr_cmd_.opr_file_md5_.c_str());
      CWX_ERROR((tss->m_szBuf2K));
      err_msg_ = tss->m_szBuf2K;
      return false;
    }
    // 形成参数
    opr_cmd_.opr_args_map_.clear();
    if (opr_cmd_.opr_args_.length()){
      XmlConfigParser parser;
      if (!parser.parse(opr_cmd_.opr_args_.c_str())){
        CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to parse shell arg for invalid xml, arg:%s", opr_cmd_.opr_args_.c_str());
        CWX_ERROR((tss->m_szBuf2K));
        err_msg_ = tss->m_szBuf2K;
        return false;
      }
      XmlTreeNode const* node = parser.getRoot()->m_pChildHead;
      string strNodeValue;
      while(node){
        if (node->m_pChildHead){
          CwxCommon::snprintf(tss->m_szBuf2K, 2047, "arg[%s] has child, it's invalid.", node->m_szElement);
          CWX_ERROR((tss->m_szBuf2K));
          err_msg_ = tss->m_szBuf2K;
          return false;
        }
        strNodeValue = "";
        list<char*>::const_iterator iter = node->m_listData.begin();
        while(iter != node->m_listData.end()){
          strNodeValue += *iter;
          iter++;
        }
        opr_cmd_.opr_args_map_[string(node->m_szElement)] = strNodeValue;
        node = node->m_next;
      }
    }
    ///cache对象
    if (opr_cmd_.cache_time_) app_->GetOprCmdCache()->AddOprCmd(dup_opr_name_, opr_cmd_);
  }
  // 获取命令执行的agent
  if (agents_.size()) {
    if (!opr_cmd_.is_agent_mutable_) {
      err_msg_ = "Can't change opr's host ip";
      return false;
    }
  } else {
    agents_  = opr_cmd_.agents_;
  }
  if (!agents_.size()) {
    err_msg_ = "No host ip";
    return false;
  }
  // 获取命令执行的参数
  if (opr_args_.size()) {
    if (!opr_cmd_.is_arg_mutable_) {
      err_msg_ = "Can't change opr's arg";
      return false;
    }
    map<string, string>::iterator opr_cmd_iter = opr_cmd_.opr_args_map_.begin();
    while(opr_cmd_iter != opr_cmd_.opr_args_map_.end()) {
      if (opr_args_.find(opr_cmd_iter->first) == opr_args_.end()) {
        opr_args_[opr_cmd_iter->first] = opr_cmd_iter->second;
      }
      opr_cmd_iter++;
    }
  } else {
    opr_args_ = opr_cmd_.opr_args_map_;
  }
  ///形成历史
  bool is_exec_sql = false;
  if (opr_cmd_.repeat_type_ != DcmdCenterOprCmd::DCMD_OPR_CMD_REPEAT_WITHOUT_HISTORY){
    ///记录操作历史
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
      "insert into dcmd_opr_cmd_repeat_exec_history(repeat_cmd_name, opr_cmd, run_user, timeout, ip, `repeat`,"\
      "cache_time, ip_mutable, arg_mutable, arg, utime, ctime, opr_uid) "\
      "select repeat_cmd_name, opr_cmd, run_user, timeout, ip, `repeat`,"\
      "cache_time, ip_mutable, arg_mutable, arg, now(), ctime, opr_uid from dcmd_opr_cmd_repeat_exec \
      where repeat_cmd_name='%s'", opr_name.c_str());
    if (-1 == my->execute(tss->sql_)){
      CwxCommon::snprintf(tss->m_szBuf2K, 2047, "Failure to exec sql:%s, err=%s",  tss->sql_, my->getErrMsg());
      CWX_ERROR((tss->m_szBuf2K));
      err_msg_ = tss->m_szBuf2K;
      my->rollback();
      my->disconnect();
      return false;
    }
    is_exec_sql = true;
  }
  if (is_exec_sql) my->commit();
  return true;
}

///检查脚本是否改变
bool DcmdCenterOprTask::IsScriptChanged(DcmdTss* tss) {
  string opr_file;
  string file_content;
  string err_msg;
  DcmdCenterConf::opr_cmd_file(app_->config().common().opr_script_path_,
    opr_cmd_.opr_file_, opr_file);
  if (!tss->ReadFile(opr_file.c_str(), file_content, err_msg)){
    CWX_ERROR((err_msg_.c_str()));
    return true;
  }
  return file_content != opr_cmd_.opr_script_content_;
}

int DcmdCenterOprTask::noticeActive(CwxTss* ThrEnv) {
  DcmdTss* tss= (DcmdTss*)ThrEnv;
  setTaskState(TASK_STATE_WAITING);
  if (is_dup_opr_) {
    if (!FetchDupOprCmd(tss)){
      setTaskState(TASK_STATE_FINISH);
      is_failed_ = true;
      return -1;
    }
  } else {
    if (!FetchOprCmd(tss)){
      setTaskState(TASK_STATE_FINISH);
      is_failed_ = true;
      return -1;
    }
  }
  CWX_UINT64 timeStamp = opr_cmd_.opr_timeout_;
  timeStamp *= 1000000;
  timeStamp += CwxDate::getTimestamp();
  this->setTimeoutValue(timeStamp);

  dcmd_api::AgentOprCmd  opr_cmd;
  CwxCommon::toString(opr_cmd_id_, tss->m_szBuf2K, 10);
  opr_cmd.set_opr_id(tss->m_szBuf2K);
  opr_cmd.set_name(opr_cmd_.opr_name_);
  opr_cmd.set_run_user(opr_cmd_.opr_run_user_);
  opr_cmd.set_timeout(opr_cmd_.opr_timeout_);
  opr_cmd.set_script(opr_cmd_.opr_script_content_);
  dcmd_api::KeyValue* kv;
  map<string, string>::iterator iter = opr_args_.begin();
  while(iter != opr_args_.end()) {
    kv = opr_cmd.add_args();
    kv->set_key(iter->first);
    kv->set_value(iter->second);
    ++iter;
  }
  if (!opr_cmd.SerializeToString(&tss->proto_str_)) {
    CWX_ERROR(("Failure to pack opr cmd msg."));
    err_msg_ = "Failure to pack opr cmd msg.";
    setTaskState(TASK_STATE_FINISH);
    is_failed_ = true;
    return -1;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_OPR_CMD, getTaskId(),
    tss->proto_str_.length());
  CwxMsgBlock* msg = CwxMsgBlockAlloc::pack(head, tss->proto_str_.c_str(),
    tss->proto_str_.length());
  if (!msg) {
    CWX_ERROR(("Failure to pack opr cmd msg for no memory"));
    exit(1);
  }
  CwxMsgBlock* send_block = NULL;
  agent_num_ = agents_.size();
  receive_num_ = 0;
  agent_conns_ = new DcmdAgentConnect[agent_num_];
  agent_replys_ = new DcmdCenterAgentOprReply[agent_num_];
  ///发送msg
  set<string>::iterator agent_iter = agents_.begin();
  uint32_t conn_id = 0;
  uint32_t index = 0;
  while(agent_iter != agents_.end()){
    if (!send_block) send_block = CwxMsgBlockAlloc::clone(msg);
    if (!DcmdCenterH4AgentOpr::SendAgentMsg(app_,
      *agent_iter,
      getTaskId(),
      send_block,
      conn_id))
    {
      CWX_ERROR(("Failure send msg to agent:%s for opr:%s", agent_iter->c_str(), opr_cmd_.opr_name_.c_str()));
      agent_conns_[index].conn_id_ = 0;
      agent_replys_[index].is_send_failed_ = true;
      receive_num_++;
    }else{
      send_block = NULL;
      agent_conns_[index].conn_id_ = conn_id;
    }
    agent_conns_[index].agent_ip_ = *agent_iter;
    index++;
    agent_iter++;
  }
  CwxMsgBlockAlloc::free(msg);
  if (send_block) CwxMsgBlockAlloc::free(send_block);
  if (receive_num_ == agent_num_){
    setTaskState(TASK_STATE_FINISH);
  }
  return 0;
}
void DcmdCenterOprTask::execute(CwxTss* pThrEnv) {
  if (CwxTaskBoardTask::TASK_STATE_INIT == getTaskState()) {
    is_reply_timeout_ = false;
    is_failed_ = false;
    agent_num_ = 0;
    receive_num_ = 0;
    agent_conns_ = NULL;
    agent_replys_ = NULL;
    getTaskBoard()->noticeActiveTask(this, pThrEnv);
  }
  if (CwxTaskBoardTask::TASK_STATE_FINISH == getTaskState()) {
    Reply(pThrEnv);
    delete this;
  }
}
void DcmdCenterOprTask::Reply(CwxTss* pThrEnv) {
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  uint16_t index = 0;
  dcmd_api::UiExecOprCmdReply reply;
  reply.set_client_msg_id(client_msg_id_);
  if (is_failed_) {
    reply.set_state(dcmd_api::DCMD_STATE_FAILED);
    reply.set_err(err_msg_);
  } else {
    reply.set_state(dcmd_api::DCMD_STATE_SUCCESS);
  }
  dcmd_api::AgentOprCmdReply* agent_reply=NULL;
  if (!is_failed_){
    ///输出发送失败的host
    for (index =0; index < agent_num_; index++){
      agent_reply = reply.add_result();
      agent_reply->set_ip(agent_conns_[index].agent_ip_);
      agent_reply->set_result("");
      agent_reply->set_err("");
      agent_reply->set_status(agent_replys_[index].status_);
      if (agent_replys_[index].is_send_failed_){
        agent_reply->set_err("Lost connected.");
        agent_reply->set_state(dcmd_api::DCMD_STATE_FAILED);
      } else if (!agent_replys_[index].is_exec_success) {
        agent_reply->set_err(agent_replys_[index].err_msg_);
        agent_reply->set_state(dcmd_api::DCMD_STATE_FAILED);
      } else if (!agent_replys_[index].recv_msg_) {
        agent_reply->set_err("timeout");
        agent_reply->set_state(dcmd_api::DCMD_STATE_FAILED);
      } else {
        agent_reply->set_result(agent_replys_[index].result_);
        agent_reply->set_state(dcmd_api::DCMD_STATE_SUCCESS);
      }
    }
  }
  DcmdCenterH4Admin::ReplyExecOprCmd(app_, tss, reply_conn_id_, msg_task_id_, &reply);
}
}  // dcmd

