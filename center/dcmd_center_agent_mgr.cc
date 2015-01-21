#include "dcmd_center_agent_mgr.h"
#include "dcmd_center_app.h"

namespace dcmd {
  bool DcmdCenterAgentMgr::AddConn(uint32_t conn_id, char const* conn_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    if (conn_agents_.find(conn_id) != conn_agents_.end()) return false;
    DcmdAgentConnect* agent = new DcmdAgentConnect(conn_ip, conn_id);
    conn_agents_[conn_id] = agent;
    return true;
  }
  bool DcmdCenterAgentMgr::RemoveConn(uint32_t conn_id) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<uint32_t, DcmdAgentConnect*>::iterator conn_iter = conn_agents_.find(conn_id);
    if (conn_iter == conn_agents_.end()) return false;
    ///如果已经auth，则删除认证的信息
    if (conn_iter->second->agent_ip_.length())  ip_agents_.erase(conn_iter->second->agent_ip_);
    delete conn_iter->second;
    conn_agents_.erase(conn_iter);
    return true;
  }
  int DcmdCenterAgentMgr::Auth(uint32_t conn_id,  string const& agent_ip,
    string const& version, string const & report_ips, string const& hostname,
    string& old_conn_ip, uint32_t& old_conn_id, string& old_hostname)
  {
    CWX_ASSERT(agent_ip.length());
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<uint32_t, DcmdAgentConnect*>::iterator conn_iter =  conn_agents_.find(conn_id);
    // 若连接不存在，则返回2，在多线程环境下此是可能的
    if (conn_iter == conn_agents_.end()) return 2; 
    map<string, DcmdAgentConnect*>::iterator ip_iter = ip_agents_.find(agent_ip);
    if (ip_iter != ip_agents_.end()){
      old_conn_ip = ip_iter->second->conn_ip_;
      old_conn_id = ip_iter->second->conn_id_;
      old_hostname = ip_iter->second->hostname_;
      return 1;
    }
    conn_iter->second->agent_ip_ = agent_ip;
    conn_iter->second->version_ = version;
    conn_iter->second->report_agent_ips_ = report_ips;
    conn_iter->second->hostname_ = hostname;
    ip_agents_[agent_ip] = conn_iter->second;
    return 0; 
  }
  void DcmdCenterAgentMgr::UnAuth(string const& agent_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<string, DcmdAgentConnect*>::iterator ip_iter = ip_agents_.find(agent_ip);
    if (ip_iter == ip_agents_.end()) return;
    ip_iter->second->agent_ip_.erase();
    ip_iter->second->is_master_report_reply_ = false;
    ip_iter->second->report_agent_ips_.clear();
    ip_agents_.erase(ip_iter);
  }
  int DcmdCenterAgentMgr::MasterNoticeReply(uint32_t conn_id) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<uint32_t, DcmdAgentConnect*>::iterator conn_iter =  conn_agents_.find(conn_id);
    if (conn_iter == conn_agents_.end()) return 2;
    if (!conn_iter->second->agent_ip_.length()) return 1;
    conn_iter->second->is_master_report_reply_ = true;
    return 0;
  }
  bool DcmdCenterAgentMgr::IsExistAgentIp(string const& agent_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    return ip_agents_.find(agent_ip) != ip_agents_.end();
  }
  bool DcmdCenterAgentMgr::IsMasterNoticeReply(string const& agent_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<string, DcmdAgentConnect*>::iterator iter = ip_agents_.find(agent_ip);
    if (iter == ip_agents_.end()) return false;
    return iter->second->is_master_report_reply_;
  }
  void DcmdCenterAgentMgr::ClearMasterNoticeReportReply() {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<uint32_t, DcmdAgentConnect*>::iterator conn_iter = conn_agents_.begin();
    while (conn_iter != conn_agents_.end()){
      conn_iter->second->is_master_report_reply_ = false;
      ++conn_iter;
    }
  }
  void DcmdCenterAgentMgr::Heatbeat(uint32_t conn_id, string const& host_name) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<CWX_UINT32, DcmdAgentConnect*>::iterator conn_iter =  conn_agents_.find(conn_id);
    if (conn_iter != conn_agents_.end()) {
      conn_iter->second->last_heatbeat_time_ = time(NULL);
      conn_iter->second->hostname_ = host_name;
    }
  }

  bool DcmdCenterAgentMgr::SendMsg(string const& agent_ip, CwxMsgBlock* msg,
    uint32_t&  conn_id)
  {
    conn_id = 0;
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      map<string, DcmdAgentConnect*>::iterator iter = ip_agents_.find(agent_ip);
      if (iter == ip_agents_.end()) return false;
      conn_id = iter->second->conn_id_;
    }
    msg->send_ctrl().setConnId(conn_id);
    if (0 == app_->sendMsgByConn(msg)) return true;
    app_->noticeCloseConn(conn_id);
    return false;
  }
  bool DcmdCenterAgentMgr::SendMsg(uint32_t conn_id, CwxMsgBlock* msg) {
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      map<CWX_UINT32, DcmdAgentConnect*>::iterator iter = conn_agents_.find(conn_id);
      if (iter == conn_agents_.end()) return false;
    }
    msg->send_ctrl().setConnId(conn_id);
    if (0 == app_->sendMsgByConn(msg)) return true;
    app_->noticeCloseConn(conn_id);
    return false;
  }
  void DcmdCenterAgentMgr::BroadcastMsg(CwxMsgBlock* msg) {
    set<CWX_UINT32> conns;
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      map<CWX_UINT32, DcmdAgentConnect*>::iterator iter = conn_agents_.begin();
      while(iter != conn_agents_.end()){
        if (iter->second->agent_ip_.length()) conns.insert(iter->first);
        ++iter;
      }
    }
    CwxMsgBlock* block = NULL;
    set<CWX_UINT32>::iterator iter = conns.begin();
    while(iter != conns.end()) {
      if (!block) block = CwxMsgBlockAlloc::clone(msg);
      block->send_ctrl().setConnId(*iter);
      if (0 == app_->sendMsgByConn(block)) block = NULL;
      ++iter;
    }
    if (block) CwxMsgBlockAlloc::free(block);
    CwxMsgBlockAlloc::free(msg);
  }
  void DcmdCenterAgentMgr::CheckHeatbeat() {
    list<DcmdAgentConnect> conns;
    uint32_t now = time(NULL);
    uint32_t heatbeat = 2 * app_->config().common().heatbeat_internal_;
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      map<CWX_UINT32, DcmdAgentConnect*>::iterator iter = conn_agents_.begin();
      while(iter != conn_agents_.end()){
        if (iter->second->last_heatbeat_time_ +  heatbeat < now) {
          conns.push_back(*iter->second);
        } else if (!iter->second->agent_ip_.length() &&
          (iter->second->create_time_ + heatbeat < now))
        {
          conns.push_back(*iter->second);
        }
        ++iter;
      }
    }
    {
      list<DcmdAgentConnect>::iterator iter = conns.begin();
      while(iter != conns.end()){
        CWX_INFO(("Close conn for without heatbeat, conn_ip[%s], agent_ip[%s]",
          iter->conn_ip_.c_str(), iter->agent_ip_.length()?iter->agent_ip_.c_str():""));
        app_->noticeCloseConn(iter->conn_id_);
        ++iter;
      }
    }
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      DcmdIllegalAgentConnect* agent=NULL;
      list<DcmdIllegalAgentConnect*>::iterator iter = illegal_agent_list_.begin();
      while(iter != illegal_agent_list_.end()){
        if ((*iter)->conn_time_ +  app_->config().common().illegal_agent_block_second_ > now) break; 
        agent = *iter;
        illegal_agent_list_.erase(iter);
        illegal_agent_map_.erase(agent->conn_ip_);
        delete agent;
        iter = illegal_agent_list_.begin();
      }
    }
  }
  void DcmdCenterAgentMgr::RefreshAgent() {
    uint32_t now = time(NULL);
    if (ip_table_last_load_time_ + app_->config().common().ip_refresh_interanl_ < now) {
      LoadNode();
    }
    if (!ip_table_) return;
    list<DcmdAgentConnect> conns;
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      map<string, DcmdAgentConnect*>::iterator iter = ip_agents_.begin();
      while(iter != ip_agents_.end()){
        if (ip_table_->find(iter->first) == ip_table_->end())
          conns.push_back(*iter->second);
        ++iter;
      }
    }
    {
      list<DcmdAgentConnect>::iterator iter = conns.begin();
      while(iter != conns.end()){
        CWX_INFO(("Close conn for without agent, conn_ip[%s], agent_ip[%s]",
          iter->conn_ip_.c_str(),
          iter->agent_ip_.length()?iter->agent_ip_.c_str():""));
        app_->noticeCloseConn(iter->conn_id_);
        ++iter;
      }
    }
  }
  void DcmdCenterAgentMgr::RefreshIpState(uint32_t now) {
    if (ip_table_last_load_time_ + app_->config().common().ip_refresh_interanl_ < now) {
      LoadNode();
    }
  }
  // 获取agent的状态信息
  void DcmdCenterAgentMgr::GetAgentStatus(list<string> const& agent_ips,
    bool fetch_version, dcmd_api::UiAgentInfoReply& result)
  {
    DcmdAgentConnect* conn;
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    result.clear_agentinfo();
    result.set_state(dcmd_api::DCMD_STATE_SUCCESS);
    result.clear_err();
    dcmd_api::AgentInfo* agent_info;
    if (agent_ips.begin() != agent_ips.end()) {
      list<string>::const_iterator ip_iter;
      map<string, DcmdAgentConnect*>::iterator agent_iter;
      ip_iter = agent_ips.begin();
      while(ip_iter != agent_ips.end()) {
        agent_iter = ip_agents_.find(*ip_iter);
        agent_info = result.add_agentinfo();
        agent_info->set_ip(*ip_iter);
        if (agent_iter == ip_agents_.end()){
          agent_info->set_state(dcmd_api::AGENT_UN_CONNECTED);
          agent_info->set_connected_ip("");
          agent_info->set_reported_ip("");
          agent_info->set_hostname("");
          if (fetch_version) agent_info->set_version("");
        }else{
          conn = agent_iter->second;
          if (!conn->agent_ip_.length()) {
            agent_info->set_state(dcmd_api::AGENT_UN_AUTH);
          } else if (!conn->is_master_report_reply_) {
            agent_info->set_state(dcmd_api::AGENT_UN_REPORTED);
          } else {
            agent_info->set_state(dcmd_api::AGENT_CONNECTED);
          }
          agent_info->set_connected_ip(conn->conn_ip_);
          agent_info->set_reported_ip(conn->report_agent_ips_);
          agent_info->set_hostname(conn->hostname_);
          if (fetch_version) agent_info->set_version(conn->version_);
        }
        ++ip_iter;
      }
    } else { // 获取所有的ip
      map<uint32_t, DcmdAgentConnect*>::iterator iter=conn_agents_.begin();
      while(iter != conn_agents_.end()) {
        conn = iter->second;
        agent_info = result.add_agentinfo();
        agent_info->set_ip(conn->agent_ip_);
        if (!conn->agent_ip_.length()) {
          agent_info->set_state(dcmd_api::AGENT_UN_AUTH);
        } else if (!conn->is_master_report_reply_) {
          agent_info->set_state(dcmd_api::AGENT_UN_REPORTED);
        } else {
          agent_info->set_state(dcmd_api::AGENT_CONNECTED);
        }
        agent_info->set_connected_ip(conn->conn_ip_);
        agent_info->set_reported_ip(conn->report_agent_ips_);
        agent_info->set_hostname(conn->hostname_);
        if (fetch_version) agent_info->set_version(conn->version_);
        ++iter;
      }
    }
  }
  bool DcmdCenterAgentMgr::GetConnIp(uint32_t conn_id, string& conn_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<CWX_UINT32, DcmdAgentConnect*>::iterator iter = conn_agents_.find(conn_id);
    if(iter == conn_agents_.end()) return false;
    conn_ip = iter->second->conn_ip_;
    return true;
  }
  bool DcmdCenterAgentMgr::GetAgentIp(uint32_t conn_id, string& agent_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<uint32_t, DcmdAgentConnect*>::iterator iter = conn_agents_.find(conn_id);
    if(iter == conn_agents_.end()) return false;
    if (!iter->second->agent_ip_.length()) return false;
    agent_ip = iter->second->agent_ip_;
    return true;
  }
  void DcmdCenterAgentMgr::CloseAgent(string const& agent_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<string, DcmdAgentConnect*>::iterator iter = ip_agents_.find(agent_ip);
    if(iter != ip_agents_.end()) app_->noticeCloseConn(iter->second->conn_id_);
  }
  bool DcmdCenterAgentMgr::ComfirmAgentIpByReportedIp(list<string> const& report_ips,
    string& agent_ip)
  {
    uint32_t now = time(NULL);
    if (ip_table_last_load_time_ + app_->config().common().ip_refresh_interanl_ < now){
      LoadNode();
    }
    if (!ip_table_) return false;
    list<string>::const_iterator iter = report_ips.begin();
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      while(iter != report_ips.end()) {
        if (ip_table_->find(*iter) != ip_table_->end()) {
          agent_ip = *iter;
          return true;
        }
        ++iter;
      }
    }
    // 直接从数据库中获取
    {
      string ips;
      string ip;
      iter = report_ips.begin();
      while(iter != report_ips.end()){
        ip = *iter;
        CwxCommon::trim(ip);
        dcmd_escape_mysql_string(ip);
        if (ip.length()){
          if (ips.length()) ips += ",";
          ips += "'";
          ips += ip;
          ips += "'";
        }
        ++iter;
      }
      if (!ips.length()) return false;
      string sql = "select ip from dcmd_node where ip in (";
      sql += ips + ")";
      Mysql* my = app_->GetTaskMysql();
      if (!app_->CheckMysql(my)) return false;
      if (!my->query(sql.c_str())){
        my->freeResult();
        CWX_ERROR(("Failure to fetch node. err:%s", my->getErrMsg()));
        return false;
      }
      bool is_null = false;
      while(1==my->next()){
        agent_ip = my->fetch(0, is_null);
        {
          CwxMutexGuard<CwxMutexLock>  lock(&lock_);
          if (ip_table_) ip_table_->insert(agent_ip);
        }
        my->freeResult();
        return true;
      }
    }
    return false;
  }

  bool DcmdCenterAgentMgr::AddInvalidConn(string const& conn_ip,
    string const& report_ips, string const& hostname, string const& version) 
  {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    if (illegal_agent_map_.find(conn_ip) != illegal_agent_map_.end()) return false;
    DcmdIllegalAgentConnect* agent = new DcmdIllegalAgentConnect(conn_ip, report_ips, hostname, version);
    illegal_agent_map_[conn_ip] = agent;
    illegal_agent_list_.push_back(agent);
    return true;
  }
  bool DcmdCenterAgentMgr::IsInvalidConnIp(string const& conn_ip) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    return illegal_agent_map_.find(conn_ip) != illegal_agent_map_.end();
  }
  void DcmdCenterAgentMgr::GetInvalidAgent(dcmd_api::UiInvalidAgentInfoReply& result) {
    result.clear_agentinfo();
    result.set_state(dcmd_api::DCMD_STATE_SUCCESS);
    result.clear_err();
    dcmd_api::AgentInfo* agent_info=NULL;
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      list<DcmdIllegalAgentConnect*>::iterator iter = illegal_agent_list_.begin();
      while (iter != illegal_agent_list_.end()) {
        agent_info = result.add_agentinfo();
        agent_info->set_ip((*iter)->conn_ip_);
        agent_info->set_state(dcmd_api::AGENT_UN_CONNECTED);
        agent_info->set_connected_ip((*iter)->conn_ip_);
        agent_info->set_reported_ip((*iter)->report_agent_ips_);
        agent_info->set_hostname((*iter)->hostname_);
        agent_info->set_version((*iter)->version_);
        ++ iter;
      }
    }
  }
  void DcmdCenterAgentMgr::LoadNode() {
    CWX_INFO(("Refresh ip table......"));
    Mysql* my = app_->GetTaskMysql();
    if (!app_->CheckMysql(my)) return;
    set<string>* ip_table= NULL;
    ///map<string, bool>* ip_state = NULL;
    bool is_null = false;
    if (!my->query("select ip from dcmd_node")) {
      CWX_ERROR(("Failure to query node from node. err:%s", my->getErrMsg()));
      return;
    }
    ip_table= new set<string>;
    ///ip_state = new map<string, bool>;
    while(1 == my->next()) {
      ip_table->insert(my->fetch(0, is_null));
      ///ip_state->insert(pair<string, bool>(my->fetch(0, is_null),
          ///strtoul(my->fetch(1, is_null), NULL, 0)==0?false:true));
    }
    my->freeResult();
    {
      CwxMutexGuard<CwxMutexLock>  lock(&lock_);
      if (ip_table_) delete ip_table_;
      ip_table_ = ip_table;
      ip_table_last_load_time_ = time(NULL);
    }
  }
  // 获取Agent主机名，返回0：不存在；1：认证的agent；2：未认证的agent
  int DcmdCenterAgentMgr::GetAgentHostName(string const& agent_ip, string & hostname) {
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    hostname.erase();
    // 先从认证的主机map获取
    {
      map<string, DcmdAgentConnect*>::iterator iter =  ip_agents_.find(agent_ip);
      if (iter != ip_agents_.end()) {
        hostname = iter->second->hostname_;
        return 1;
      }
    }
    // 再从未认证的主机获取
    {
      map<string, DcmdIllegalAgentConnect*>::iterator iter = illegal_agent_map_.find(agent_ip);
      if (iter != illegal_agent_map_.end()) {
        hostname = iter->second->hostname_;
        return 1;
      }
    }
    return 0;
  }
  // 认证illegal的主机
  bool DcmdCenterAgentMgr::AuthIllegalAgent(string const& conn_ip) {
    // 基于连接ip的map。
    CwxMutexGuard<CwxMutexLock>  lock(&lock_);
    map<string, DcmdIllegalAgentConnect*>::iterator iter = illegal_agent_map_.find(conn_ip);
    if (iter != illegal_agent_map_.end()) {
      DcmdIllegalAgentConnect* illegalConn = iter->second;
      list<DcmdIllegalAgentConnect*>::iterator list_iter =illegal_agent_list_.begin();
      while(list_iter != illegal_agent_list_.end()) {
        if (illegalConn == *list_iter) {
          illegal_agent_list_.erase(list_iter);
          break;
        }
        list_iter++;
      }
      illegal_agent_map_.erase(iter);
      delete illegalConn;
      return true;
    }
    return false;
  }


}  // dcmd

