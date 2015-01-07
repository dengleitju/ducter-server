namespace dcmd {
  inline DcmdCenterTask* DcmdCenterTaskMgr::GetTask(uint32_t task_id) {
    map<uint32_t, DcmdCenterTask*>::iterator iter = all_tasks_.find(task_id);
    if (iter == all_tasks_.end()) return NULL;
    return iter->second;
  }
  inline DcmdCenterSubtask* DcmdCenterTaskMgr::GetSubTask(uint64_t subtask_id) {
    map<uint64_t, DcmdCenterSubtask*>::iterator iter =  all_subtasks_.find(subtask_id);
    if (iter == all_subtasks_.end()) return NULL;
    return iter->second;
  }
  inline DcmdCenterAgent* DcmdCenterTaskMgr::GetAgent(string const& agent_ip) {
    map<string, DcmdCenterAgent*>::iterator iter = agents_.find(agent_ip);
    if (iter == agents_.end()) return NULL;
    return iter->second;
  }
  inline bool DcmdCenterTaskMgr::UpdateTaskValid(DcmdTss* tss, bool is_commit, 
    uint32_t task_id, bool is_valid, char const* err_msg)
  {
    string str_tmp = err_msg?string(err_msg):"";
    dcmd_escape_mysql_string(str_tmp);
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, 
      "update dcmd_task set valid=%d, err_msg='%s' where task_id=%d",
      is_valid?1:0, is_valid?"":str_tmp.c_str(), task_id);
    return ExecSql(tss, is_commit);
  }
  inline bool DcmdCenterTaskMgr::UpdateTaskState(DcmdTss* tss, bool is_commit,
    uint32_t task_id, uint8_t state)
  {
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, 
      "update dcmd_task set state=%d where task_id=%d", state, task_id);
    return ExecSql(tss, is_commit);
  }
  inline bool DcmdCenterTaskMgr::UpdateSubtaskState(DcmdTss* tss, bool is_commit,
    uint64_t subtask_id, uint8_t state, char const* err_msg)
  {
    char buf[64];
    string str_tmp = string(err_msg);
    dcmd_escape_mysql_string(str_tmp);
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, 
      "update dcmd_task_node set state=%d , err_msg = '%s' where subtask_id=%s",
      state, str_tmp.c_str(), CwxCommon::toString(subtask_id, buf, 10));
    return ExecSql(tss, is_commit);
  }

  inline bool DcmdCenterTaskMgr::UpdateCmdState(DcmdTss* tss, bool is_commit,
    uint64_t cmd_id, uint8_t state, char const* err_msg)
  {
    char buf[64];
    string str_tmp = string(err_msg);
    dcmd_escape_mysql_string(str_tmp);
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, 
      "update dcmd_command set state=%d, err_msg = '%s' where cmd_id=%s",
      state, str_tmp.c_str(), CwxCommon::toString(cmd_id, buf, 10));
    return ExecSql(tss, is_commit);
  }
  inline bool DcmdCenterTaskMgr::UpdateTaskInfo(DcmdTss* tss, bool is_commit,
    uint32_t task_id, uint32_t con_rate, uint32_t timeout,
    bool is_auto, uint32_t uid)
  {
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, 
      "update dcmd_task set concurrent_rate=%d, timeout=%d, auto=%d,"\
      "opr_uid=%d, utime=now() where task_id=%u",
      con_rate, timeout, is_auto?1:0, uid, task_id);
    return ExecSql(tss, is_commit);
  }
  inline uint64_t DcmdCenterTaskMgr::InsertCommand(DcmdTss* tss, bool is_commit, uint32_t uid,
    uint32_t task_id, uint64_t subtask_id, char const* svr_pool,
    uint32_t svr_pool_id, char const* service, char const* ip,
    uint8_t cmt_type, uint8_t state, char const* err_msg)
  {
    string svr_pool_str(svr_pool?svr_pool:"");
    string service_str(service?service:"");
    string ip_str(ip?ip:"");
    string err_msg_str(err_msg?err_msg:"");
    dcmd_escape_mysql_string(svr_pool_str);
    dcmd_escape_mysql_string(service_str);
    dcmd_escape_mysql_string(ip_str);
    dcmd_escape_mysql_string(err_msg_str);
    char cmd_id_sz[65];
    char subtask_id_sz[65];
    uint64_t cmd_id = ++next_cmd_id_;
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
      "insert into dcmd_command(cmd_id, task_id, subtask_id, svr_pool, svr_pool_id, svr_name, ip,"\
      "cmd_type, state, err_msg, utime, ctime, opr_uid) "\
      " values (%s, %u, %s, '%s', %u, '%s','%s', %u, %u, '%s', now(), now(), %u)",
      CwxCommon::toString(cmd_id, cmd_id_sz,10), task_id,
      CwxCommon::toString(subtask_id, subtask_id_sz, 10),svr_pool_str.c_str(),
      svr_pool_id, service_str.c_str(), ip_str.c_str(), cmt_type, state,
      err_msg_str.c_str(), uid);
    if (!ExecSql(tss, is_commit)) return 0;
    return cmd_id;
  }
  inline bool DcmdCenterTaskMgr::ExecSql(DcmdTss* tss, bool is_commit) {
    if (-1 == mysql_->execute(tss->sql_)) {
      tss->err_msg_ = string("Failure to exec sql, err:") + mysql_->getErrMsg();
      tss->err_msg_ += string(". sql:") + tss->sql_;
      CWX_ERROR((tss->err_msg_.c_str()));
      mysql_->rollback();
      return false;
    }
    if (is_commit && !mysql_->commit()) {
      tss->err_msg_ = string("Failure to commit sql, err:") + mysql_->getErrMsg();
      tss->err_msg_ += string(". sql:") + tss->sql_;
      CWX_ERROR((tss->err_msg_.c_str()));
      mysql_->rollback();
      return false;
    }
    return true;
  }
  inline void DcmdCenterTaskMgr::RemoveTaskFromMem(DcmdCenterTask* task) {
    // 加此锁是为了防止与获取subtask process的admin线程冲突
    CwxMutexGuard<CwxMutexLock> lock(&lock_);
    all_tasks_.erase(task->task_id_);
    map<uint64_t, DcmdCenterSubtask*>* subtasks;
    map<uint64_t, DcmdCenterSubtask*>::iterator subtasks_iter;
    map<string, DcmdCenterSvrPool*>::iterator pool_iter = task->pools_.begin();
    while(pool_iter != task->pools_.end()){
      subtasks = &pool_iter->second->all_subtasks_;
      subtasks_iter = subtasks->begin();
      while(subtasks_iter != subtasks->end()){
        if (subtasks_iter->second->exec_cmd_){
          RemoveCmd(subtasks_iter->second->exec_cmd_);
        }
        all_subtasks_.erase(subtasks_iter->second->subtask_id_);
        delete subtasks_iter->second;
        subtasks_iter++;
      }
      pool_iter++;
    }
    delete task;
  }
  inline bool DcmdCenterTaskMgr::UpdateSubtaskInfo(DcmdTss* tss, uint64_t subtask_id,
    bool is_commit, uint32_t* state, bool* is_skip,
    bool is_start_time, bool is_finish_time, char const* err_msg, 
    char const* process)
  {
    char tmp_buf[64];
    string value;
    string sql;
    uint32_t init_len = 0;
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
      "update dcmd_task_node set ");
    sql = tss->sql_;
    init_len = sql.length();
    if (state){
      CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
        " state=%d ", *state);
      if (sql.length() != init_len) sql += ",";
      sql += tss->sql_;
    }
    if (is_skip){
      CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
        " ignored=%d ", *is_skip?1:0);
      if (sql.length() != init_len) sql += ",";
      sql += tss->sql_;
    }
    if (is_start_time){
      CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
        " start_time=now() ");
      if (sql.length() != init_len) sql += ",";
      sql += tss->sql_;
    }
    if (is_finish_time){
      CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
        " finish_time=now() ");
      if (sql.length() != init_len) sql += ",";
      sql += tss->sql_;
    }
    if (err_msg){
      value = err_msg;
      dcmd_escape_mysql_string(value);
      CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
        " err_msg='%s' ", value.c_str());
      if (sql.length() != init_len) sql += ",";
      sql += tss->sql_;
    }
    if (process){
      value = process;
      dcmd_escape_mysql_string(value);
      CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
        " process='%s' ", value.c_str());
      if (sql.length() != init_len) sql += ",";
      sql += tss->sql_;
    }
    CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
      ", utime=now() where subtask_id = %s ", 
      CwxCommon::toString(subtask_id, tmp_buf, 10));
    sql += tss->sql_;

    if (-1 == mysql_->execute(sql.c_str())){
      tss->err_msg_ = string("Failure to exec sql, err:") + mysql_->getErrMsg();
      tss->err_msg_ += string("; sql:") + sql;
      CWX_ERROR((tss->err_msg_.c_str()));
      mysql_->rollback();
      return false;
    }
    if (is_commit && !mysql_->commit()){
      tss->err_msg_ = string("Failure to exec sql, err:") + mysql_->getErrMsg();
      tss->err_msg_ = string("; sql:") + sql;
      CWX_ERROR((tss->err_msg_.c_str()));
      mysql_->rollback();
      return false;
    }
    return true;
  }

  inline void DcmdCenterTaskMgr::FillCtrlCmd(dcmd_api::AgentTaskCmd& cmd,
    uint64_t cmd_id, dcmd_api::CmdType cmd_type, string const& agent_ip,
    string const& svr_name, string const& svr_pool, DcmdCenterSubtask* subtask
    )
  {
    char buf[64];
    CwxCommon::toString(cmd_id, buf, 10);
    cmd.set_cmd(buf);
    cmd.set_task_cmd(kDcmdSysCmdCancel);
    cmd.set_cmd_type(cmd_type);
    if (subtask) {
      sprintf(buf, "%u", subtask->task_id_);
      cmd.set_task_id(buf);
      CwxCommon::toString(subtask?subtask->subtask_id_:0, buf, 10);
      cmd.set_subtask_id(buf);
    }
    cmd.set_ip(agent_ip);
    cmd.set_svr_name(svr_name);
    cmd.set_svr_pool(svr_pool);
  }
  // 计算任务及svr_pool的信息
  inline bool DcmdCenterTaskMgr::CalcTaskStatsInfo(DcmdTss* tss, bool is_commit, 
    DcmdCenterTask* task)
  {
    if (dcmd_api::TASK_INIT != task->state_) {
      uint8_t task_state = task->CalcTaskState();
      if (task_state != task->state_) {
        if (!UpdateTaskState(tss, false, task->task_id_, task_state)) return false;
        task->state_ = task_state;
      }
      map<string, DcmdCenterSvrPool*>::iterator iter = task->pools_.begin();
      while (iter != task->pools_.end()) {
        if (iter->second->IsSubtaskStatsChanged()) {
          iter->second->UpdateSubtaskStats();
          CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
            "update dcmd_task_service_pool set undo_node=%d, doing_node=%d,finish_node=%d,"\
            "fail_node=%d, ignored_fail_node=%d, ignored_doing_node=%d, state=%d "\
            "where task_id=%d and svr_pool_id=%d",
            iter->second->undo_host_num(),
            iter->second->doing_host_num(),
            iter->second->finished_host_num(),
            iter->second->failed_host_num(),
            iter->second->ignored_failed_host_num(),
            iter->second->ignored_doing_host_num(),
            iter->second->GetState(task->max_current_rate_),
            task->task_id_,
            iter->second->svr_pool_id_);
          if (!ExecSql(tss, false)) return false;
        }
        ++iter;
      }
    }
    if (is_commit && !mysql_->commit()){
      tss->err_msg_ = string("Failure to commit, err:") + mysql_->getErrMsg();
      CWX_ERROR((tss->err_msg_.c_str()));
      mysql_->rollback();
      return false;
    }
    return true;
  }
}  // dcmd
