#include "dcmd_agent_app.h"

#include <CwxDate.h>
#include <CwxFile.h>
#include <CwxMd5.h>
#include <net/if.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <linux/sockios.h>
#include <sys/ioctl.h>
#include <arpa/inet.h>
#include <pwd.h>
#include <unistd.h>

namespace dcmd {
// 获取主机的所有网卡的IP列表。0：成功；-1：失败
static int get_host_ip_addr(list<string>& ips){
  int s=-1, num=0, i=0;
  struct ifconf conf;
  struct ifreq *ifr;
  char buff[BUFSIZ];
  string ip;
  s = socket(PF_INET, SOCK_DGRAM, 0);
  conf.ifc_len = BUFSIZ;
  conf.ifc_buf = buff;
  ::ioctl(s, SIOCGIFCONF, &conf);
  num = conf.ifc_len / sizeof(struct ifreq);
  ifr = conf.ifc_req;
  for(i=0;i < num;i++){
    struct sockaddr_in *sin = (struct sockaddr_in *)(&ifr->ifr_addr);
    ::ioctl(s, SIOCGIFFLAGS, ifr);
    ip = inet_ntoa(sin->sin_addr);
    ips.push_back(ip);
    ifr++;
  }
  return 0;
}
static bool OutputShellEnv(FILE* fd, char const* name, string const& value,
  char* szErr2K, char const* fileName)
{
  string local_value = value;
  dcmd_remove_spam(local_value);
  if (local_value.length()){
    if (fprintf(fd, "export %s='%s'\n", name, local_value.c_str()) < 0){
      CwxCommon::snprintf(szErr2K, 2047, "Failure to write run shell file:%s, errno=%d",
        fileName, errno);
      return false;
    }
  }else{
    if (fprintf(fd, "export %s=\n", name) < 0){
      CwxCommon::snprintf(szErr2K, 2047, "Failure to write run shell file:%s, errno=%d",
        fileName, errno);
      return false;
    }
  }
  return true;
}
DcmdAgentApp::DcmdAgentApp() {
  master_ = NULL;
  next_opr_cmd_id_ = 0;
  opr_overflow_threshold_ = kDefOprOverflowThreshold;
  data_buf_ = NULL;
  data_buf_len_ = 0;
  err_2k_[0] = 0x00;
}
DcmdAgentApp::~DcmdAgentApp() {}
int DcmdAgentApp::init(int argc, char** argv) {
  string err_msg;
  // 首先调用架构的init api
  if (CwxAppFramework::init(argc, argv) == -1) return -1;
  // 检查是否通过-f指定了配置文件，若没有，则采用默认的配置文件
  if ((NULL == this->getConfFile()) || (strlen(this->getConfFile()) == 0))
    this->setConfFile("dcmd_agent.conf");
  // 加载配置文件，若失败则退出
  if (0 != config_.Init(getConfFile())) {
    CWX_ERROR((config_.err_msg()));
    return -1;
  }
  // 设置运行日志的输出level
  if (config_.conf().is_debug_){
    setLogLevel(CwxLogger::LEVEL_ERROR|CwxLogger::LEVEL_INFO|
      CwxLogger::LEVEL_WARNING|CwxLogger::LEVEL_DEBUG);
  }else{
    setLogLevel(CwxLogger::LEVEL_ERROR|CwxLogger::LEVEL_INFO|
      CwxLogger::LEVEL_WARNING);
  }
  return 0;
}
int DcmdAgentApp::initRunEnv(){
  ::sleep(5); // 等待安装完成
  // 设置系统的时钟间隔，最小刻度为1ms，此为0.2s。
  this->setClick(200);//0.2s
  // 设置工作目录
  this->setWorkDir(config_.conf().work_home_.c_str());
  // 设置循环运行日志的数量
  this->setLogFileNum(config_.conf().log_file_num_);
  // 设置每个日志文件的大小
  this->setLogFileSize(config_.conf().log_file_msize_*1024*1024);
  // 调用架构的initRunEnv，使以上设置的参数生效
  if (CwxAppFramework::initRunEnv() == -1 ) return -1;
  // set version
  this->setAppVersion(kDcmdAgentVersion);
  // set last modify date
  this->setLastModifyDatetime(kDcmdAgentModifyDate);
  // set compile date
  this->setLastCompileDatetime(CWX_COMPILE_DATE(_BUILD_DATE));
  // 将加载的配置文件信息输出到日志文件中，以供查看检查
  config_.OutputConfig();
  // 清空环境
  Reset();
  // 初始化目录
  string path;
  GetTaskScriptPath(path);
  CWX_INFO(("Check task script path: %s......", path.c_str()));
  if (!InitPath(path, true)) return -1;

  GetDoingTaskPath(path);
  CWX_INFO(("Check doing task path: %s......", path.c_str()));
  if (!InitPath(path, false)) return -1;

  GetTaskOutputPath(path);
  CWX_INFO(("Check task output path: %s......", path.c_str()));
  if (!InitPath(path, false)) return -1;

  GetOprScriptPath(path);
  CWX_INFO(("Check opr script path: %s......", path.c_str()));
  if (!InitPath(path, true)) return -1;

  GetOprOutputPath(path);
  CWX_INFO(("Check opr output path: %s......", path.c_str()));
  if (!InitPath(path, true)) return -1;

  string ips;
  {// 获取ip地址
    agent_ips_.clear();
    if (0 != get_host_ip_addr(agent_ips_)) {
      CWX_ERROR(("Failure to get host's ip list, errno=%d", errno));
      return -1;
    }
    agent_ips_.sort();
    list<string>::iterator iter = agent_ips_.begin();
    while(iter != agent_ips_.end()){
      if (memcmp(iter->c_str(), "127.", 4) == 0){
        iter = agent_ips_.erase(iter);
      }
      ips += *iter + ";";
      iter++;
    }
    CWX_INFO(("Agent ip list:%s", ips.c_str()));
    if (agent_ips_.begin() == agent_ips_.end()){
      CWX_ERROR(("Can't get host ip addr, it's empty."));
      return -1;
    }
  }
  { // 获取主机的名字 
    char host_name[256];
    if (0 != gethostname(host_name, 255)) {
      CWX_ERROR(("Failed to get hostname, errno=%d", errno));
      return -1;
    }
    CWX_INFO(("Host name:%s", host_name));
    host_name_ = host_name;
  }
  // 加载已经完成的任务
  loadDoingTasks();

  { // 连接控制中心
    list<CwxHostInfo>::const_iterator iter = config_.conf().centers_.begin();
    uint32_t host_id = 1;
    AgentCenter* center = NULL;
    while(iter != config_.conf().centers_.end()){
      int ret = 0;
      CWX_INFO(("Connecting to center:%s:%u......",
        iter->getHostName().c_str(), iter->getPort()));
      if (0 > (ret = this->noticeTcpConnect(SVR_TYPE_CENTER,
        host_id,
        iter->getHostName().c_str(),
        iter->getPort(),
        false,
        2,
        6)))
      {
        CWX_ERROR(("Can't register connect for center: addr=%s, port=%d",
          iter->getHostName().c_str(),
          iter->getPort()));
        return -1;
      }
      center = new AgentCenter();
      center->host_name_ = iter->getHostName();
      sprintf(err_2k_, ":%u", iter->getPort());
      center->host_name_ += err_2k_;
      center->host_port_ = iter->getPort();
      center->last_heatbeat_time_ = 0;
      center->opr_overflow_threshold_ = kDefIpTableRefreshSecond;
      center->conn_id_ = ret;
      center->host_id_ = host_id;
      center->is_connected_ = false;
      center->is_auth_failed_ = false;
      center->is_auth_ = false;
      center->heatbeat_internal_ = kDefHeatbeatSecond; 
      CWX_ASSERT(center_map_.find(center->conn_id_) == center_map_.end());
      center_map_[center->conn_id_] = center;
      host_id++;
      iter++;
    }
  }
  return 0;
}
void DcmdAgentApp::onTime(CwxTimeValue const& current) {
  static uint32_t base_time = 0;
  static uint32_t last_check_time = 0;
  uint32_t  now = time(NULL);
  bool is_clock_back = IsClockBack(base_time, now);
  // 调用基类的onTime函数
  CwxAppFramework::onTime(current);
  if (is_clock_back || (last_check_time + 3600 < now)){
    last_check_time = now;
    // 清除失效的任务输出结果
    CheckExpiredTaskOutput(now);
  }
  if (!svr_pool_map_.size() && !opr_cmd_map_.size()) {
    int status = 0;
    ::waitpid(-1, &status, WNOHANG);
  }
  // 检查未发送的消息
  CheckSubtaskResultReply();
  // 检查任务、操作指令
  CheckTaskAndOprCmd();
}
void DcmdAgentApp::onSignal(int signum){
//  int status = 0;
  switch(signum){
  case SIGQUIT: 
    // 若监控进程通知退出，则推出
    CWX_INFO(("Recieve exit signal, exit right now."));
    this->stop();
    break;
//  case SIGCHLD:
    // cgi的进程退出，进行进程状态信息的回收，否则将形成僵尸进程
//    wait(&status);
//    break;
  default:
    // 其他信号，全部忽略
    CWX_INFO(("Recieve signal=%d, ignore it.", signum));
    break;
  }
}
int DcmdAgentApp::onConnCreated(CwxAppHandler4Msg& conn, bool& , bool& ) {
  map<uint32_t, AgentCenter*>::iterator iter;
  iter = center_map_.find(conn.getConnInfo().getConnId());
  CWX_ASSERT(iter != center_map_.end());
  CWX_ASSERT(!iter->second->is_connected_);
  AgentCenter* center = iter->second;
  CWX_INFO(("Report agent to center:%s", center->host_name_.c_str()));
  // 向center报告自己的状态信息
  CwxMsgBlock* msg = NULL;
  dcmd_api::AgentReport report;
  report.set_version(kDcmdAgentVersion);
  report.set_hostname(host_name_);
  list<string>::iterator ip_iter = agent_ips_.begin();
  while(ip_iter != agent_ips_.end()) {
    *report.add_agent_ips()=*ip_iter;
    ip_iter++;
  }
  if (!report.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack heatbeat msg."));
    return -1;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_AGENT_REPORT, 0, proto_str_.length());
  msg = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!msg){
    CWX_ERROR(("Failure to pack heatbeat msg for no memory"));
    exit(1);
  }
  msg->send_ctrl().setConnId(center->conn_id_);
  msg->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  msg->send_ctrl().setHostId(0);
  msg->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(msg)){
    CWX_ERROR(("Failure to send heatbeat to center:%s  conn_id:%d",
      center->host_name_.c_str(),
      center->conn_id_));
    CwxMsgBlockAlloc::free(msg);
    return -1; 
  }
  center->is_connected_ = true;
  center->last_heatbeat_time_ = time(NULL);
  CWX_INFO(("Create connection to center:%s", center->host_name_.c_str()));
  return 0;
}

int DcmdAgentApp::onConnClosed(CwxAppHandler4Msg& conn) {
  // 控制中心的连接关闭
  AgentCenter* center = NULL;
  {
    map<uint32_t, AgentCenter*> ::iterator iter;
    iter = center_map_.find(conn.getConnInfo().getConnId());
    CWX_ASSERT(iter != center_map_.end());
    center = iter->second;
    center->is_connected_ = false;
    center->is_auth_ = false;
    center->is_auth_failed_ = false;
  }
  // 如果当前关闭的连接为master，则需要特殊处理
  bool is_lost_master = false;
  if (center == master_){
    // 将等待center master回复的消息，需要重新进入发送队列
    map<uint64_t, AgentTaskResult*> ::iterator iter = wait_reply_result_map_.begin();
    while(iter != wait_reply_result_map_.end()){
      wait_send_result_map_[iter->first] = iter->second;
      iter->second->last_send_result_time_ = 0;
      ++iter;
    }
    wait_reply_result_map_.clear();
    master_ = NULL;
    is_lost_master = true;
  }
  // 取消此连接对应的操作指令
  if (opr_cmd_map_.size()){
    map<uint64_t, AgentOprCmd*>::iterator opr_iter =  opr_cmd_map_.begin();
    uint64_t opr_id = 0;
    while(opr_iter != opr_cmd_map_.end()){
      if (opr_iter->second->center_ == center){
        if (CheckOprCmd(opr_iter->second, true)) { // cancel shell的执行
          opr_id = opr_iter->first;
          delete opr_iter->second;
          opr_cmd_map_.erase(opr_iter);
          opr_iter = opr_cmd_map_.upper_bound(opr_id);
          continue;
        }
      }
      ++ opr_iter;
    }
  }
  CWX_INFO(("Lost connection to center:%s, master:%s",
    center->host_name_.c_str(),
    is_lost_master?"true":"false"));
  return 0;
}

int DcmdAgentApp::onRecvMsg(CwxMsgBlock* msg,  CwxAppHandler4Msg& conn,
                             CwxMsgHead const& header, bool& )
{
  if (!msg) msg = CwxMsgBlockAlloc::malloc(0);
  msg->event().setMsgHeader(header);
  msg->event().setSvrId(conn.getConnInfo().getSvrId());
  msg->event().setHostId(conn.getConnInfo().getHostId());
  msg->event().setConnId(conn.getConnInfo().getConnId());
  int ret = RecvMsg(msg);
  if (msg) CwxMsgBlockAlloc::free(msg);
  return ret;
}
void DcmdAgentApp::destroy(){
  Reset();
  CwxAppFramework::destroy();
}
void DcmdAgentApp::Reset(){
  {// 释放控制中心对象map
    map<uint32_t, AgentCenter*>::iterator iter = center_map_.begin();
    while(iter != center_map_.end()){
      delete iter->second;
      iter++;
    }
    center_map_.clear();
  }
  // 将当前的master置为空
  master_ = NULL;
  {// 释放svr的task的map
    map<string, AgentSvrPoolObj*>::iterator iter = svr_pool_map_.begin();
    while(iter != svr_pool_map_.end()){
      delete iter->second;
      iter++;
    }
    svr_pool_map_.clear();
  }
  {// 释放等待回复的task执行结果
    map<uint64_t, AgentTaskResult*>::iterator iter = wait_reply_result_map_.begin();
    while(iter != wait_reply_result_map_.end()){
      delete iter->second;
      iter++;
    }
    wait_reply_result_map_.clear();
  }
  {// 释放等待发送的task执行结果
    map<uint64_t, AgentTaskResult*> ::iterator iter = wait_send_result_map_.begin();
    while(iter != wait_send_result_map_.end()){
      delete iter->second;
      iter++;
    }
    wait_send_result_map_.clear();
  }
  {// 释放所有指令对象
    map<uint64_t, AgentTaskCmd*>::iterator iter = subtask_map_.begin();
    while(iter != subtask_map_.end()){
      delete iter->second;
      iter++;
    }
    subtask_map_.clear();
  }
  // 清空未放到svr队列中的消息
  recieved_subtasks_.clear();

  {// 释放操作对象
    map<uint64_t, AgentOprCmd*>::iterator iter = opr_cmd_map_.begin();
    while(iter != opr_cmd_map_.end()){
      delete iter->second;
      iter++;
    }
    opr_cmd_map_.clear();
  }
  // 清空操作指令id
  next_opr_cmd_id_ = 0;
  // 是否buf
  if (data_buf_){
    delete [] data_buf_;
  }
  data_buf_ = NULL;
  data_buf_len_ = 0;
}
bool DcmdAgentApp::InitPath(string const& path, bool is_clean_path) {
  if (!CwxFile::isDir(path.c_str())) {
    CWX_INFO(("create directory:%s", path.c_str()));
    if (!CwxFile::createDir(path.c_str())) {
      CWX_ERROR(("Failure to create path:%s", path.c_str()));
      return false;
    }
  }
  if (0 != chmod(path.c_str(), S_IRUSR|S_IWUSR|S_IXUSR|S_IRGRP|S_IWGRP|S_IXGRP|S_IROTH|S_IWOTH|S_IXOTH)){
    CWX_ERROR(("Failure to set 'rwx-rwx-rwx' permissions for path[%s], errno=%d.", path.c_str(), errno));
    return false;
  }
  if (is_clean_path) {
    CWX_INFO(("clear directory:%s", path.c_str()));
    sprintf(err_2k_, "rm -f %s/*", path.c_str());
    ::system(err_2k_);
  }
  return true;
}
void DcmdAgentApp::CheckExpiredTaskOutput(uint32_t now){
  CWX_INFO(("Begin remove task output file."));
  string path;
  string file;
  string file_time;
  string now_time;
  uint32_t modify_time;
  list<string> file_list;
  GetTaskOutputPath(path);
  CwxFile::getDirFile(path, file_list);
  list<string>::iterator iter = file_list.begin();
  CwxDate::getDate(now, now_time);
  while(iter != file_list.end()){
    file = path + "/" + *iter;
    modify_time = CwxFile::getFileMTime(file.c_str());
    if (modify_time + kTaskCmdOutputExpireDay * 24 * 3600 < now){//remove it
      CwxDate::getDate(modify_time, file_time);
      CWX_INFO(("Remove out-date tasknode output file:%s, modify-time=%s, now=%s",
        file.c_str(), file_time.c_str(), now_time.c_str()));
      CwxFile::rmFile(file.c_str());
    }
    iter++;
  }
  CWX_INFO(("End remove task output file."));
}
void DcmdAgentApp::CheckTaskAndOprCmd(){
  // 检测心跳
  CheckHeatbeat();
  // 检查新收到的新指令
  CheckNewReceivedCmd();
  // 检查svr的task的运行状况
  CheckTasks();
  // 检查未发送给center的task处理结果，并发送给center
  CheckTaskWaitingToReply();
  // 检测操作命令
  DispatchOprCmd();
}

void DcmdAgentApp::DispatchOprCmd() {
  if (opr_cmd_map_.size()){
    map<uint64_t, AgentOprCmd*> ::iterator iter =  opr_cmd_map_.begin();
    uint64_t opr_id = 0;
    AgentOprCmd* opr_cmd = NULL;
    while(iter != opr_cmd_map_.end()) {
      opr_cmd = iter->second;
      // 检查或执行操作命令
      if (CheckOprCmd(opr_cmd, false)) { 
        // 如果认为已经完成
        opr_id = iter->first;
        opr_cmd_map_.erase(iter);
        delete opr_cmd;
        iter = opr_cmd_map_.upper_bound(opr_id);
        continue;
      }
      iter++;
    }
  }
}

void DcmdAgentApp::CheckNewReceivedCmd() {
  string unique_name;
  if (recieved_subtasks_.begin() != recieved_subtasks_.end()){
    list<AgentTaskCmd*>::iterator iter = recieved_subtasks_.begin();
    AgentSvrPoolObj* svr_pool_obj = NULL;
    map<string, AgentSvrPoolObj*>::iterator svr_pool_iter;
    while(iter != recieved_subtasks_.end()){
      //find svr pool
      unique_name = (*iter)->cmd_.app_name() + "_" + (*iter)->cmd_.svr_name() + "_" + (*iter)->cmd_.svr_pool();
      svr_pool_iter = svr_pool_map_.find(unique_name);
      if (svr_pool_iter != svr_pool_map_.end()){
        svr_pool_obj = svr_pool_iter->second;
      }else{
        svr_pool_obj = new AgentSvrPoolObj();
        svr_pool_obj->unique_name_ = unique_name;
        svr_pool_obj->app_name_ = (*iter)->cmd_.app_name();
        svr_pool_obj->svr_name_ = (*iter)->cmd_.svr_name();
        svr_pool_obj->svr_pool_name_ = (*iter)->cmd_.svr_pool();
        svr_pool_obj->processor_ = NULL;
        svr_pool_obj->running_cmd_ = NULL;
        svr_pool_map_[svr_pool_obj->svr_pool_name_] = svr_pool_obj;
      }
      // cancel存在的同一个subtask的命令
      if ((*iter)->cmd_.cmd_type() == dcmd_api::CMD_DO_SUBTASK) {
        list<AgentTaskCmd*>::iterator cmd_iter =  svr_pool_obj->cmds_.begin();
        while(cmd_iter != svr_pool_obj->cmds_.end()) {
          if ((*cmd_iter)->cmd_.cmd_type() == dcmd_api::CMD_DO_SUBTASK) {
            // 若是同一个任务的指令
            if ((*iter)->cmd_.subtask_id() == (*cmd_iter)->cmd_.subtask_id()) {
              ExecCancelSubTask(svr_pool_obj, (*iter)->cmd_.subtask_id());
            }
          }
          ++cmd_iter;
        }
      }
      svr_pool_obj->cmds_.push_back(*iter);
      ++iter;
    }
    recieved_subtasks_.clear();
  }
}
void DcmdAgentApp::CheckTasks() {
  if (svr_pool_map_.size()) {
    map<string, AgentSvrPoolObj*>::iterator iter = svr_pool_map_.begin();
    while(iter != svr_pool_map_.end()){
      CheckSvrTask(iter->second);
      ++iter;
    }
    // 删除没有任务的svr
    iter = svr_pool_map_.begin();
    while(iter != svr_pool_map_.end()){
      if ((iter->second->cmds_.begin() == iter->second->cmds_.end())
        && !iter->second->running_cmd_)
      {
        CWX_ASSERT(!iter->second->processor_);
        delete iter->second;
        svr_pool_map_.erase(iter);
        iter = svr_pool_map_.begin();
        continue;
      }
      iter++;
    }
  }
}

void DcmdAgentApp::CheckTaskWaitingToReply() {
  if (master_){
    CwxMsgBlock* block = NULL;
    AgentTaskResult* result = NULL;
    map<uint64_t, AgentTaskResult*>::iterator iter = wait_send_result_map_.begin();
    while(iter != wait_send_result_map_.end()){
      result  = iter->second;
      if (!result->result_.SerializeToString(&proto_str_)) {
        CWX_ERROR(("Failure to serialize dcmd_api::AgentTaskResult"));
        exit(0);
      }
      CwxMsgHead head(0,
        0,
        dcmd_api::MTYPE_AGENT_SUBTASK_CMD_RESULT,
        iter->second->msg_taskid_,
        proto_str_.length());
      block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
      if (!block) {
        CWX_ERROR(("Failure to pack subtask result package for no memory"));
        exit(0);
      }
      block->send_ctrl().setConnId(master_->conn_id_);
      block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
      block->send_ctrl().setHostId(0);
      block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE); 
      if (-1 == sendMsgByConn(block)) {
        CWX_ERROR(("Failure to send subtask result to center:%s,"\
          "cmd:%s, subtask:%s, conn_id:%d",
          master_->host_name_.c_str(),
          result->result_.cmd().c_str(),
          result->result_.subtask_id().c_str(),
          master_->conn_id_));
        CwxMsgBlockAlloc::free(block);
        return ;
      }
      CWX_DEBUG(("Send subtask result to center:%s, cmd:%s, subtask:%s",
        result->result_.cmd().c_str(),
        result->result_.cmd().c_str(),
        result->result_.subtask_id().c_str()));
      // 将处理结果从待发送队列已送到等待回复的队列
      wait_reply_result_map_[iter->first] = result;
      result->last_send_result_time_ = time(NULL);
      wait_send_result_map_.erase(iter);
      iter = wait_send_result_map_.begin();
    }
  }
}

// 检查center对于subtask result的回复
void DcmdAgentApp::CheckSubtaskResultReply() {
  if (master_){
    CwxMsgBlock* block = NULL;
    AgentTaskResult* result = NULL;
    uint32_t  now = time(NULL);
    map<uint64_t, AgentTaskResult*>::iterator iter = wait_reply_result_map_.begin();
    while(iter != wait_reply_result_map_.end()){
      if (now > iter->second->last_send_result_time_ + kSubtaskResultResendSecond) {
        result  = iter->second;
        if (!result->result_.SerializeToString(&proto_str_)) {
          CWX_ERROR(("Failure to serialize dcmd_api::AgentTaskResult"));
          exit(0);
        }
        CwxMsgHead head(0,
          0,
          dcmd_api::MTYPE_AGENT_SUBTASK_CMD_RESULT,
          iter->second->msg_taskid_,
          proto_str_.length());
        block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
        if (!block) {
          CWX_ERROR(("Failure to pack subtask result package for no memory"));
          exit(0);
        }
        block->send_ctrl().setConnId(master_->conn_id_);
        block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
        block->send_ctrl().setHostId(0);
        block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE); 
        if (-1 == sendMsgByConn(block)) {
          CWX_ERROR(("Failure to send subtask result to center:%s,"\
            "cmd:%s, subtask:%s, conn_id:%d",
            master_->host_name_.c_str(),
            result->result_.cmd().c_str(),
            result->result_.subtask_id().c_str(),
            master_->conn_id_));
          CwxMsgBlockAlloc::free(block);
          return ;
        }
        CWX_DEBUG(("Send subtask result to center:%s, cmd:%s, subtask:%s",
          result->result_.cmd().c_str(),
          result->result_.cmd().c_str(),
          result->result_.subtask_id().c_str()));
        result->last_send_result_time_ = now;
      }
      iter++;
    }
  }
}


void DcmdAgentApp::CheckHeatbeat(){
  static uint32_t time_base = 0;
  static uint32_t last_check_heatbeat = time(NULL);
  uint32_t now = time(NULL);
  bool is_clock_back = IsClockBack(time_base, now);
  if (is_clock_back || (last_check_heatbeat < now) ){
    last_check_heatbeat = now;
    map<uint32_t, AgentCenter*>::iterator iter = center_map_.begin();
    CwxMsgBlock* block = NULL;
    AgentCenter* center = NULL;
    while(iter != center_map_.end()){
      center = iter->second;
      if (!center->is_connected_){
        ++iter; // 如果没有连接，则忽略
        continue;
      }
      if (!is_clock_back &&
        (center->last_heatbeat_time_ + center->heatbeat_internal_ > now)) {
        ++iter;// 如果没有超时，则继续
        continue;
      }
      // 判断host name是否改变
      { // 获取主机的名字
        char host_name[256];
        if (0 == gethostname(host_name, 255) && host_name != host_name_) {
          host_name_ = host_name;
        }
      }
      // 发送心跳
      CwxMsgHead head(0, 0, dcmd_api::MTYPE_AGENT_HEATBEAT, 0, host_name_.length());
      block = CwxMsgBlockAlloc::pack(head, host_name_.c_str(), host_name_.length());
      if (!block) {
        CWX_ERROR(("Failure to pack heatbeat package for no memory"));
        exit(0);
      }
      block->send_ctrl().setConnId(center->conn_id_);
      block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
      block->send_ctrl().setHostId(0);
      block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE); 
      if (-1 == sendMsgByConn(block)) {
        CWX_ERROR(("Failure to send heatbeat to center:%s  conn_id:%d",
          center->host_name_.c_str(),
          center->conn_id_));
        CwxMsgBlockAlloc::free(block);
        return ; 
      }
      CWX_DEBUG(("Send heatbeat to:%s", center->host_name_.c_str()));
      center->last_heatbeat_time_ = now;
      ++iter;
    }
  }
}

void DcmdAgentApp::CheckSvrTask(AgentSvrPoolObj* svr_pool_obj) {
  AgentTaskResult* result = NULL;
  // 检查当前的进程
  if (svr_pool_obj->processor_) CheckRuningSubTask(svr_pool_obj, false);
  // 首先处理控制指令
  {
    list<AgentTaskCmd*>::iterator iter = svr_pool_obj->cmds_.begin();
    while(iter != svr_pool_obj->cmds_.end()){
      if ((*iter)->cmd_.cmd_type() != dcmd_api::CMD_DO_SUBTASK) {
        if ((*iter)->cmd_.cmd_type() == dcmd_api::CMD_CANCEL_SUBTASK){
          ExecCtrlTaskCmdForCancelSubTask(svr_pool_obj, *iter);
        } else if ((*iter)->cmd_.cmd_type() == dcmd_api::CMD_CANCEL_SVR_SUBTASK) {
          ExecCtrlTaskCmdForCancelAll(svr_pool_obj, *iter);
        } else {
          // 未知的命令
          CWX_INFO(("Unknown command, svr=%s, svr_pool=%s, cmd_id=%s, sub_task=%s, type=%s, cmd_type=%d",
            svr_pool_obj->svr_name_.c_str(),
            svr_pool_obj->svr_pool_name_.c_str(),
            (*iter)->cmd_.cmd().c_str(),
            (*iter)->cmd_.subtask_id().c_str(),
            (*iter)->cmd_.task_cmd().c_str(),
            (*iter)->cmd_.cmd_type()));
          result = new AgentTaskResult();
          FillTaskResult(**iter, *result, "", false, "Unknown cmd type");
          wait_send_result_map_[result->cmd_id_] = result;
          svr_pool_obj->cmds_.erase(iter);
        }
        iter = svr_pool_obj->cmds_.begin();
      } else {
        iter++;
      }
    }
  }
  // 检测是否要fork进程执行subtask
  if (!svr_pool_obj->processor_ && (svr_pool_obj->cmds_.begin() != svr_pool_obj->cmds_.end())) {
    CWX_ASSERT(!svr_pool_obj->running_cmd_);
    string err_msg;
    AgentTaskCmd* cmd = *svr_pool_obj->cmds_.begin();
    svr_pool_obj->cmds_.pop_front();
    CWX_INFO(("Execute subtask for svr:%s, svr_pool:%s, task-type=%s,"\
      "task_id=%s  subtask=%s cmd-id=%s",
      svr_pool_obj->svr_name_.c_str(),
      svr_pool_obj->svr_pool_name_.c_str(),
      cmd->cmd_.task_cmd().c_str(),
      cmd->cmd_.task_id().c_str(),
      cmd->cmd_.subtask_id().c_str(),
      cmd->cmd_.cmd().c_str()));
    if (!PrepareSubtaskRunEnv(cmd, err_msg)){
      CWX_ERROR(("Failure to prepare subtask's execution: task_id=%s "\
        "subtask=%s cmd_id=%s. err=%s",
        cmd->cmd_.task_id().c_str(),
        cmd->cmd_.subtask_id().c_str(),
        cmd->cmd_.cmd().c_str(),
        err_msg.c_str()));
      result = new AgentTaskResult();
      FillTaskResult(*cmd, *result, "", false, err_msg);
      wait_send_result_map_[result->cmd_id_] = result;
      return;
    }
    if (!ExecSubTaskCmd(cmd, err_msg, svr_pool_obj->processor_)) {
      CWX_ERROR(("Failure to exec subtask: task_id=%s  subtask=%s cmd-id=%s. err=%s",
        cmd->cmd_.task_id().c_str(),
        cmd->cmd_.subtask_id().c_str(),
        cmd->cmd_.cmd().c_str(),
        err_msg.c_str()));
      svr_pool_obj->processor_ = NULL;
      result = new AgentTaskResult();
      FillTaskResult(*cmd, *result, "", false, err_msg);
      wait_send_result_map_[result->cmd_id_] = result;
      return;
    }
    // 保存doing task info
    AgentDoingTaskInfo task_info;
    task_info.msg_task_id_ = cmd->msg_taskid_;
    GetTaskResultFile(cmd->cmd_.subtask_id(), task_info.task_result_file_);
    task_info.task_id_ = cmd->cmd_.task_id();
    task_info.subtask_id_ = cmd->cmd_.subtask_id();
    task_info.cmd_id_ = cmd->cmd_.cmd();
    task_info.svr_pool_ = cmd->cmd_.svr_pool();
    task_info.task_cmd_ = cmd->cmd_.task_cmd();
    saveDoingTask(task_info);
    svr_pool_obj->running_cmd_ = cmd;
  }
}

bool DcmdAgentApp::CheckOprCmd(AgentOprCmd* opr_cmd, bool is_cancel){
  int ret=0;
  int status = 0;
  string out_file;
  string script_file;
  string run_shell_script_file;
  string err_msg;
  GetOprRunScriptFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, script_file);
  GetOprOuputFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, out_file);
  GetOprRunScriptShellFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, run_shell_script_file);
  if (is_cancel) {
    if(opr_cmd->processor_) { // 进程在运行
      if (0 == opr_cmd->processor_->TryWait(err_msg)) {
        opr_cmd->processor_->Kill(true);
      }
    }
    CwxFile::rmFile(script_file.c_str());
    CwxFile::rmFile(out_file.c_str());
    CwxFile::rmFile(run_shell_script_file.c_str());
    return true;
  }
  bool is_success = false;
  string content;
  uint32_t now;
  do{
    now = time(NULL);
    if (0 == opr_cmd->processor_) { // 执行operation
      CWX_DEBUG(("Begin to exec operation, id=%s  name=%s user=%s content=%s",
        opr_cmd->cmd_.opr_id().c_str(),
        opr_cmd->cmd_.name().c_str(),
        opr_cmd->cmd_.run_user().c_str(),
        opr_cmd->cmd_.script().c_str()));
      // 准备运行环境
      if (!PrepareOprRunEnv(opr_cmd, err_msg)) {
        CWX_ERROR(("Failure to prepare opr run env for: name:%s  id=%d  err=%s",
          opr_cmd->cmd_.name().c_str(),
          opr_cmd->cmd_.opr_id().c_str(),
          err_msg.c_str()));
        break; // 执行失败
      }
      if (!ExecOprCmd(opr_cmd, err_msg, opr_cmd->processor_)){
        CWX_ERROR(("Failure to exec opr cmd for: name:%s  id=%s  err=%s",
          opr_cmd->cmd_.name().c_str(),
          opr_cmd->cmd_.opr_id().c_str(),
          err_msg.c_str()));
        break; // 执行失败
      }
      return false;
    }
    // 检查正在执行shell的状态
    ret =opr_cmd->processor_->TryWait(err_msg);
    if (0 == ret){// 进程还在运行
      if (now < opr_cmd->begin_time_ + opr_cmd->cmd_.timeout() ) {
        // 正常运行而且没有超时
        return false;
      }
      opr_cmd->processor_->Kill(true);
      err_msg = "Timeout";
      break;
    }
    if (2 == ret) {//进程非正常退出
      status = opr_cmd->processor_->return_code();
    }
    // 获取进程的输出
    off_t size = CwxFile::getFileSize(out_file.c_str());
    if (-1 == size) {
      CwxCommon::snprintf(err_2k_, 2047, "opr-cmd's output file is missed. file:%s",
        out_file.c_str());
      err_msg = err_2k_;
      break;
    }
    if (size > opr_cmd->center_->max_package_size_ * 1024 * 1024){
      CwxCommon::snprintf(err_2k_, 2047, "opr-cmd's output file is too huge:%u. max:%u",
        size, opr_cmd->center_->max_package_size_ * 1024 * 1024);
      err_msg = err_2k_;
      break;
    }
    if (!CwxFile::readTxtFile(out_file, content)) {
      CwxCommon::snprintf(err_2k_, 2047, "Failure to read output file:%s, errno=%d",
        out_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    is_success = true;
  }while(0);

  // 回复执行结果，成功或失败
  CWX_DEBUG(("Reply opr-cmd result, id=%s  name=%s  success=%s",
    opr_cmd->cmd_.opr_id().c_str(),
    opr_cmd->cmd_.name().c_str(),
    is_success?"true":"false"));

  ReplyOprCmd(opr_cmd->center_,
    opr_cmd->msg_taskid_,
    is_success,
    status,
    content.c_str(),
    err_msg.c_str());
  CwxFile::rmFile(script_file.c_str());
  CwxFile::rmFile(out_file.c_str());
  CwxFile::rmFile(run_shell_script_file.c_str());
  return true;
}

int DcmdAgentApp::RecvMsg(CwxMsgBlock*& msg){
  AgentCenter* center = NULL;
  map<uint32_t, AgentCenter*>::iterator iter = center_map_.find(msg->event().getConnId());
  CWX_ASSERT(iter != center_map_.end());
  CWX_ASSERT(msg);
  center = iter->second;
  if (dcmd_api::MTYPE_AGENT_REPORT_R == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_AGENT_REPORT_R, center:%s", center->host_name_.c_str()));
    return ReportReply(msg, center);
  }else if (dcmd_api::MTYPE_CENTER_MASTER_NOTICE == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_CENTER_MASTER_NOTICE, center:%s", center->host_name_.c_str()));
    return MasterChanged(msg, center);
  }else if (dcmd_api::MTYPE_CENTER_SUBTASK_CMD == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_CENTER_SUBTASK_CMD, center:%s", center->host_name_.c_str()));
    return SubTaskCmdRecieved(msg, center);
  }else if (dcmd_api::MTYPE_AGENT_SUBTASK_CMD_RESULT_R == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_AGENT_SUBTASK_CMD_RESULT_R, center:%s", center->host_name_.c_str()));
    return SubTaskResultReply(msg, center);
  }else if (dcmd_api::MTYPE_CENTER_OPR_CMD == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_CENTER_OPR_CMD, center:%s", center->host_name_.c_str()));
    return OprCmdRecieved(msg, center);
  }else if (dcmd_api::MTYPE_CENTER_AGENT_SUBTASK_OUTPUT == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_CENTER_AGENT_SUBTASK_OUTPUT, center:%s", center->host_name_.c_str()));
    return FetchTaskOutputResultRecieved(msg, center);
  }else if (dcmd_api::MTYPE_CENTER_AGENT_RUNNING_TASK == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_CENTER_AGENT_RUNNING_TASK, center:%s", center->host_name_.c_str()));
    return GetRunTaskRecieved(msg, center);
  }else if (dcmd_api::MTYPE_CENTER_AGENT_RUNNING_OPR == msg->event().getMsgHeader().getMsgType()){
    CWX_INFO(("Receive MTYPE_CENTER_AGENT_RUNNING_OPR, center:%s", center->host_name_.c_str()));
    return GetRunOprRecieved(msg, center);
  }
  CWX_INFO(("Receive invalid msg type from center:%s, msg-type=%d, reconnect it.\n",
    center->host_name_.c_str(), msg->event().getMsgHeader().getMsgType()));
  return -1;
}

void DcmdAgentApp::CheckRuningSubTask(AgentSvrPoolObj* svr_pool_obj, bool is_cancel){
  AgentTaskResult* result = NULL;
  bool is_kill = false;
  CWX_ASSERT(0 != svr_pool_obj->processor_);
  CWX_ASSERT(svr_pool_obj->running_cmd_);
  int ret = 0;
  string err_msg;
  ret =svr_pool_obj->processor_->TryWait(err_msg);
  if (0 == ret){// 进程还在运行
    if (!is_cancel){
      if (master_ && svr_pool_obj->running_cmd_->cmd_.output_process()){
        // 如果需要检查进度
        CheckSubTaskProcess(svr_pool_obj);
      }
      return;
    }
    // 否则kill进程
    svr_pool_obj->processor_->Kill(true);
    is_kill = true;
  }
  if (-1 == ret){// 进程不存在
    CWX_INFO(("svr[%s]:svr_pool[%s]'s task[%s] for type[%s] doesn't exist, proc=%d.", 
      svr_pool_obj->svr_name_.c_str(),
      svr_pool_obj->svr_pool_name_.c_str(),
      svr_pool_obj->running_cmd_->cmd_.task_id().c_str(),
      svr_pool_obj->running_cmd_->cmd_.task_cmd().c_str(),
      svr_pool_obj->processor_->pid()));
  }else{// it's finish
    CWX_INFO(("svr_pool[%s]@svr[%s]'s task[%s] for type[%s] is finished.", 
      svr_pool_obj->svr_pool_name_.c_str(),
      svr_pool_obj->svr_name_.c_str(),
      svr_pool_obj->running_cmd_->cmd_.task_id().c_str(),
      svr_pool_obj->running_cmd_->cmd_.task_cmd().c_str()));
  }
  // 处理结果
  string out_process;
  bool is_success = true;
  bool is_finish = false;
  if (!is_kill){
    LoadSubTaskResult(svr_pool_obj->running_cmd_->cmd_.subtask_id(),
      out_process,
      is_success,
      err_msg,
      is_finish,
      false);
  }else{
    is_success = false;
    err_msg = "Subtask is canceled.";
    out_process = "";
  }
  result = new AgentTaskResult();
  FillTaskResult(*svr_pool_obj->running_cmd_, *result, out_process, is_success, err_msg);
  CWX_INFO(("svr_pool[%s]:svr[%s]'s cmd finished, task-cmd=%s task_id=%s subtask_id=%s, cmd_id=%s, state=%s, err=%s",
    svr_pool_obj->svr_pool_name_.c_str(),
    svr_pool_obj->svr_name_.c_str(),
    svr_pool_obj->running_cmd_->cmd_.task_cmd().c_str(),
    svr_pool_obj->running_cmd_->cmd_.task_id().c_str(),
    svr_pool_obj->running_cmd_->cmd_.subtask_id().c_str(),
    svr_pool_obj->running_cmd_->cmd_.cmd().c_str(),
    is_success?"success":"fail",
    is_success?"":err_msg.c_str()));
  wait_send_result_map_[result->cmd_id_] = result;
  // 删除任务脚本
  string script_file;
  string script_sh_file;
  GetTaskRunScriptFile(svr_pool_obj->running_cmd_->cmd_.subtask_id(), script_file);
  GetTaskRunScriptShellFile(svr_pool_obj->running_cmd_->cmd_.subtask_id(), script_sh_file);
  CwxFile::rmFile(script_file.c_str());
  CwxFile::rmFile(script_sh_file.c_str());
  svr_pool_obj->processor_ = NULL;
  svr_pool_obj->running_cmd_ = NULL;
}

void DcmdAgentApp::ExecCtrlTaskCmdForCancelSubTask(AgentSvrPoolObj* svr_pool_obj,
  AgentTaskCmd* cmd)
{
  AgentTaskResult* result = NULL;
  CWX_ASSERT(cmd->cmd_.task_cmd() == kDcmdSysCmdCancel);
  CWX_INFO(("Exec cancel command for all, svr=%s  svr_pool=%s cmd_id=%s  subtask_id=%s",
    svr_pool_obj->svr_name_.c_str(),
    svr_pool_obj->svr_pool_name_.c_str(),
    cmd->cmd_.cmd().c_str(),
    cmd->cmd_.subtask_id().length()?cmd->cmd_.subtask_id().c_str():""));
  bool bCanceled = false;
  // 检查是否需要cancel当前正在执行的任务
  if (svr_pool_obj->running_cmd_ && 
    (svr_pool_obj->running_cmd_->cmd_.subtask_id() == cmd->cmd_.subtask_id()))
  {
    CWX_INFO(("Cancel running subtask, svr=%s  svr_pool=%s cmd_id=%s  sub_task=%s",
      svr_pool_obj->svr_name_.c_str(),
      svr_pool_obj->svr_pool_name_.c_str(),
      svr_pool_obj->running_cmd_->cmd_.cmd().c_str(),
      svr_pool_obj->running_cmd_->cmd_.subtask_id().c_str()));
    CheckRuningSubTask(svr_pool_obj, true);
    bCanceled = true;
  }
  // 将cmds中cancel之前的任务全部cancel掉
  if (!bCanceled) {
    list<AgentTaskCmd*>::iterator iter_cancel = svr_pool_obj->cmds_.begin();
    while(iter_cancel != svr_pool_obj->cmds_.end()){
      if((*iter_cancel)->cmd_.cmd() !=  cmd->cmd_.cmd()) {
        if ((*iter_cancel)->cmd_.subtask_id() ==  cmd->cmd_.subtask_id()) {
          CWX_INFO(("Cancel subtask, svr=%s  svr_pool=%s cmd_id=%s  sub_task=%s",
            svr_pool_obj->svr_name_.c_str(),
            svr_pool_obj->svr_pool_name_.c_str(),
            (*iter_cancel)->cmd_.cmd().c_str(),
            (*iter_cancel)->cmd_.subtask_id().c_str()));
          result = new AgentTaskResult();
          FillTaskResult(*(*iter_cancel), *result, "", false, "Subtask is canceled.");
          wait_send_result_map_[result->cmd_id_] = result;
          svr_pool_obj->cmds_.erase(iter_cancel);
          break;
        }
      }else{
        break; // 到了cancel指令自身
      }
    }
  }
  // 处理cancel指令
  list<AgentTaskCmd*>::iterator iter = svr_pool_obj->cmds_.begin();
  while(iter != svr_pool_obj->cmds_.end()){
    if ((*iter)->cmd_id_ == cmd->cmd_id_) break;
    iter++;
  }
  CWX_ASSERT(iter != svr_pool_obj->cmds_.end());
  result = new AgentTaskResult();
  FillTaskResult(*cmd, *result, "0", true, "");
  wait_send_result_map_[result->cmd_id_] = result;
  // 将cancel指令从队里中删除
  svr_pool_obj->cmds_.erase(iter);
}

// 处理控制指令
void DcmdAgentApp::ExecCancelSubTask(AgentSvrPoolObj* svr_pool_obj, string const& subtask_id) {
  AgentTaskResult* result = NULL;
  CWX_INFO(("Exec cancel command for all, svr=%s svr_pool=%s  subtask_id=%s",
    svr_pool_obj->svr_name_.c_str(),
    svr_pool_obj->svr_pool_name_.c_str(),
    subtask_id.c_str()));
  bool bCanceled = false;
  // 检查是否需要cancel当前正在执行的任务
  if (svr_pool_obj->running_cmd_ && 
    (svr_pool_obj->running_cmd_->cmd_.subtask_id() == subtask_id))
  {
    CWX_INFO(("Cancel running subtask, svr=%s, svr_pool=%s, sub_task=%s",
      svr_pool_obj->svr_name_.c_str(),
      svr_pool_obj->svr_pool_name_.c_str(),
      subtask_id.c_str()));
    CheckRuningSubTask(svr_pool_obj, true);
    bCanceled = true;
  }
  // 将cmds中cancel之前的任务全部cancel掉
  if (!bCanceled) {
    list<AgentTaskCmd*>::iterator iter_cancel = svr_pool_obj->cmds_.begin();
    while(iter_cancel != svr_pool_obj->cmds_.end()){
      if ((*iter_cancel)->cmd_.subtask_id() ==  subtask_id) {
        CWX_INFO(("Cancel subtask, svr=%s, svr_pool=%s, sub_task=%s",
          svr_pool_obj->svr_name_.c_str(),
          svr_pool_obj->svr_pool_name_.c_str(),
          subtask_id.c_str()));
        result = new AgentTaskResult();
        FillTaskResult(*(*iter_cancel), *result, "", false, "Subtask is canceled.");
        wait_send_result_map_[result->cmd_id_] = result;
        svr_pool_obj->cmds_.erase(iter_cancel);
        break;
      }
    }
  }
}

void DcmdAgentApp::ExecCtrlTaskCmdForCancelAll(AgentSvrPoolObj* svr_pool_obj,
  AgentTaskCmd* cmd)
{
  AgentTaskResult* result = NULL;
  CWX_ASSERT(cmd->cmd_.task_cmd() == kDcmdSysCmdCancel);
  CWX_INFO(("Exec cancel command, svr=%s  svr_pool=%s cmd_id=%s  subtask_id=%s",
    svr_pool_obj->svr_name_.c_str(),
    svr_pool_obj->svr_pool_name_.c_str(),
    cmd->cmd_.cmd().c_str(),
    cmd->cmd_.subtask_id().length()?cmd->cmd_.subtask_id().c_str():""));
  // 检查是否需要cancel当前正在执行的任务
  if (svr_pool_obj->running_cmd_) {
    CWX_INFO(("Cancel running subtask, svr=%s svr_pool=%s cmd_id=%s  sub_task=%s",
      svr_pool_obj->svr_name_.c_str(),
      svr_pool_obj->svr_pool_name_.c_str(),
      svr_pool_obj->running_cmd_->cmd_.cmd().c_str(),
      svr_pool_obj->running_cmd_->cmd_.subtask_id().c_str()));
    CheckRuningSubTask(svr_pool_obj, true);
  }
  // 将cmds中cancel之前的任务全部cancel掉
  {
    list<AgentTaskCmd*>::iterator iter_cancel = svr_pool_obj->cmds_.begin();
    while(iter_cancel != svr_pool_obj->cmds_.end()){
      if((*iter_cancel)->cmd_.cmd() !=  cmd->cmd_.cmd()) {
        CWX_INFO(("Cancel subtask, svr=%s  svr_pool=%s cmd_id=%s  sub_task=%s",
          svr_pool_obj->svr_name_.c_str(),
          svr_pool_obj->svr_pool_name_.c_str(),
          (*iter_cancel)->cmd_.cmd().c_str(),
          (*iter_cancel)->cmd_.subtask_id().c_str()));
        result = new AgentTaskResult();
        FillTaskResult(*(*iter_cancel), *result, "", false, "Subtask is canceled.");
        wait_send_result_map_[result->cmd_id_] = result;
        svr_pool_obj->cmds_.erase(iter_cancel);
        iter_cancel = svr_pool_obj->cmds_.begin(); // 重新从begin开始
      }else{
        break; // 到了cancel指令自身
      }
    }
  }
  // 处理cancel指令
  list<AgentTaskCmd*>::iterator iter = svr_pool_obj->cmds_.begin();
  while(iter != svr_pool_obj->cmds_.end()){
    if ((*iter)->cmd_id_ == cmd->cmd_id_){
      break;
    }
    iter++;
  }
  CWX_ASSERT(iter != svr_pool_obj->cmds_.end());
  result = new AgentTaskResult();
  FillTaskResult(*cmd, *result, "", true, "");
  wait_send_result_map_[result->cmd_id_] = result;
  // 将cancel指令从队里中删除
  svr_pool_obj->cmds_.erase(iter);
}

bool DcmdAgentApp::PrepareSubtaskRunEnv(AgentTaskCmd* cmd, string& err_msg) {
  // 清除历史文件
  FILE* fd = NULL;
  string result_file;
  string output_file;
  string script_file;
  string script_sh_file;
  GetTaskResultFile(cmd->cmd_.subtask_id(), result_file);
  if (CwxFile::isFile(result_file.c_str())) CwxFile::rmFile(result_file.c_str());
  GetTaskOutputFile(output_file, cmd->cmd_.subtask_id());
  if (CwxFile::isFile(output_file.c_str())) CwxFile::rmFile(output_file.c_str());
  GetTaskRunScriptFile(cmd->cmd_.subtask_id(), script_file);
  if (CwxFile::isFile(script_file.c_str())) CwxFile::rmFile(script_file.c_str());
  GetTaskRunScriptShellFile(cmd->cmd_.subtask_id(), script_sh_file);
  if (CwxFile::isFile(script_sh_file.c_str())) CwxFile::rmFile(script_sh_file.c_str());
  // 写shell脚本
  if (!cmd->cmd_.script().length()){
    CwxCommon::snprintf(err_2k_, 2047, "subtask's script is empty.");
    err_msg = err_2k_;
    return false;
  }
  fd = fopen(script_file.c_str(), "w+");
  if (!fd){
    CwxCommon::snprintf(err_2k_, 2047, "Failure to open subtask's script file:%s, errno=%d",
      script_file.c_str(), errno);
    err_msg = err_2k_;
    return false;
  }
  if (fwrite(cmd->cmd_.script().c_str(), 1, cmd->cmd_.script().length(), fd)
    != cmd->cmd_.script().length())
  {
    fclose(fd);
    CwxCommon::snprintf(err_2k_, 2047, "Failure to write subtask's script file:%s, errno=%d",
      script_file.c_str(), errno);
    err_msg = err_2k_;
    return false;
  }
  fclose(fd);
  fd = NULL;
  bool is_success = false;
  do {
    fd = fopen(script_sh_file.c_str(), "w+");
    if (!fd){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to open subtask's shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "#!/bin/sh\n") < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write subtask's shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    //重定向信息
/*    if (fprintf(fd, "exec  1>>%s 2>>%s\n", output_file.c_str(), output_file.c_str()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write subtask's shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }*/
    if (fprintf(fd, "export DCMD_SYS_AGENT_PID=%d\n", getpid()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "export DCMD_SYS_AGENT_PPID=%d\n", getppid()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_POOL", cmd->cmd_.svr_pool(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_NAME", cmd->cmd_.svr_name(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_APP_NAME", cmd->cmd_.app_name(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_VERSION", cmd->cmd_.svr_ver(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_REPO", cmd->cmd_.svr_repo(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_USER", cmd->cmd_.svr_user(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_IP", cmd->cmd_.ip(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_PATH", cmd->cmd_.svr_path(), err_2k_, script_sh_file.c_str())) {
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "export DCMD_SVR_UPDATE_ENV=%d\n", cmd->cmd_.update_env())<0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "export DCMD_SVR_UPDATE_TAG=%d\n", cmd->cmd_.update_ver())<0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "export DCMD_SVR_NODE_MUTIL_POOL=%d\n", cmd->cmd_.node_multi_pool())<0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (!OutputShellEnv(fd, "DCMD_SVR_ENV_V", cmd->cmd_.svr_env_ver(), err_2k_, script_sh_file.c_str())){
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "export DCMD_SVR_RESULT_FILE=%s\n", result_file.c_str()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "export DCMD_SVR_PROCESS=%d\n", cmd->cmd_.output_process()?1:0) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    //输出任务的参数
    int arg_num = cmd->cmd_.task_arg_size();
    string key;
    string value;
    int i = 0;
    for (i=0; i<arg_num; i++) {
      key = string("DCMD_TASK_") + cmd->cmd_.task_arg(i).key();
      dcmd_remove_spam(key);
      value = cmd->cmd_.task_arg(i).value();
      if (!OutputShellEnv(fd, key.c_str(), value, err_2k_, script_sh_file.c_str())){
        err_msg = err_2k_;
        break;
      }
    }
    if (i < arg_num) break;
    //输出服务池子的参数
    arg_num = cmd->cmd_.svr_pool_arg_size();
    for (i=0; i<arg_num; i++) {
      key = string("DCMD_SVR_POOL_") + cmd->cmd_.svr_pool_arg(i).key();
      dcmd_remove_spam(key);
      value = cmd->cmd_.svr_pool_arg(i).value();
      if (!OutputShellEnv(fd, key.c_str(), value, err_2k_, script_sh_file.c_str())){
        err_msg = err_2k_;
        break;
      }
    }
    if (i < arg_num) break;
    //执行exec 文件
    if (fprintf(fd, "stdbuf -i0 -o0 -e0 %s>%s 2>&1;\n", script_file.c_str(),output_file.c_str()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write run shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    is_success = true;
  }while(0);
  if (fd) ::fclose(fd);
  fd = NULL;
  if (is_success){// 修改shell文件的权限
    if (0 != chmod(script_file.c_str(), S_IRUSR|S_IWUSR|S_IXUSR|S_IRGRP|S_IWGRP|S_IXGRP|S_IROTH|S_IWOTH|S_IXOTH)){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to set 'rwx-rwx-rwx' permissions for task script file:%s, errno=%d.",
        script_file.c_str(), errno);
      return false;
    }
    if (0 != chmod(script_sh_file.c_str(),  S_IRUSR|S_IWUSR|S_IXUSR|S_IRGRP|S_IWGRP|S_IXGRP|S_IROTH|S_IWOTH|S_IXOTH)){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to set 'rwx-rwx-rwx' permissions for task script sh file[%s], errno=%d.",
        script_sh_file.c_str(), errno);
      return false;
    }
  }
  return is_success;
}

// 基于subtask形成task result
void DcmdAgentApp::FillTaskResult(AgentTaskCmd const& cmd, AgentTaskResult& result,
  string const& process, bool success, string const& err_msg) {
  result.msg_taskid_ = cmd.msg_taskid_;
  result.cmd_id_ = cmd.cmd_id_;
  GetTaskResultFile(cmd.cmd_.subtask_id(), result.result_file_);
  result.result_.set_cmd(cmd.cmd_.cmd());
  result.result_.set_task_id(cmd.cmd_.task_id());
  result.result_.set_subtask_id(cmd.cmd_.subtask_id());
  result.result_.set_success(success);
  if (!success) {
    result.result_.set_err(err_msg);
  } else {
    result.result_.clear_err();
  }
  if (process.length()) {
    result.result_.set_process(process);
  } else {
    result.result_.clear_process();
  }
}

bool DcmdAgentApp::ExecSubTaskCmd(AgentTaskCmd* cmd, string& err_msg,
  DcmdProcess*& process) {
  string script_file;
  GetTaskRunScriptShellFile(cmd->cmd_.subtask_id(), script_file);
  process = new DcmdProcess(script_file);
  if (!process->Run(cmd->cmd_.has_svr_user() && cmd->cmd_.svr_user().length()?cmd->cmd_.svr_user().c_str():NULL,
    NULL,
    NULL,
    &err_msg)) {
    delete process;
    process = NULL;
    return false;
  }
  return true;
}

int DcmdAgentApp::ReportReply(CwxMsgBlock*& msg, AgentCenter* center) {
  CWX_INFO(("Receive report-reply msg from center:%s", center->host_name_.c_str()));
  proto_str_.assign(msg->rd_ptr(),msg->length());
  dcmd_api::AgentReportReply report_reply;
  if (!report_reply.ParseFromString(proto_str_)) {
    // 解析失败认为是通信错误，关闭连接
    snprintf(err_2k_, 2047,
      "Failure to unpack report reply msg, center:%s", center->host_name_.c_str());
    center->is_auth_ = false;
    center->err_msg_ = err_2k_;
    CWX_ERROR((err_2k_));
    return -1;
  }
  if (dcmd_api::DCMD_STATE_SUCCESS != report_reply.state()){
    snprintf(err_2k_, 2047, "Failure to auth to center:%s, err:%s",
      center->host_name_.c_str(), report_reply.err().c_str());
    center->is_auth_ = false;
    center->err_msg_ = err_2k_;
    CWX_ERROR((err_2k_));
    return -1;
  }else{
    CWX_INFO(("Success to report center:%s, heatbeat:%u, psize:%u",
      center->host_name_.c_str(),
      report_reply.heatbeat(),
      report_reply.package_size()));
  }

  center->is_connected_ = true;
  center->is_auth_ = true;
  center->is_auth_failed_ = false;
  center->err_msg_ = "";
  center->heatbeat_internal_ = report_reply.heatbeat();
  center->max_package_size_ = report_reply.package_size();
  center->opr_overflow_threshold_ = report_reply.opr_overflow_threshold();
  if (!center->heatbeat_internal_){
    center->heatbeat_internal_ = kDefHeatbeatSecond;
  }else if (center->heatbeat_internal_ > kMaxHeatbeatSecond){
    center->heatbeat_internal_ = kMaxHeatbeatSecond;
  }else if (center->heatbeat_internal_ < kMinHeatbeatSecond){
    center->heatbeat_internal_ = kMinHeatbeatSecond;
  }
  if (!center->max_package_size_){
    center->max_package_size_ = kDefMaxPackageMSize;
  }else if (center->max_package_size_ > kMaxMaxPackageMSize){
    center->max_package_size_ = kMaxMaxPackageMSize;
  }else if (center->max_package_size_ < kMinMaxPackageMSize){
    center->max_package_size_ = kMinMaxPackageMSize;
  }
  if (!center->opr_overflow_threshold_){
    center->opr_overflow_threshold_ = kDefOprOverflowThreshold;
  }else if (center->opr_overflow_threshold_ > KMaxOprOverflowThreshold){
    center->opr_overflow_threshold_ = KMaxOprOverflowThreshold;
  }else if (center->opr_overflow_threshold_ < kMinOprOverflowThreshold){
    center->opr_overflow_threshold_ = kMinOprOverflowThreshold;
  }
  return 0;
}

int DcmdAgentApp::MasterChanged(CwxMsgBlock*& msg, AgentCenter* center) {
  // 如果控制中心改变，则设置新的控制中心连接ID
  if (master_ != center){
    CWX_INFO(("center master is changed, prev=%s, now=%s",
      master_?master_->host_name_.c_str():"",
      center->host_name_.c_str()));
    master_ = center;
  }
  opr_overflow_threshold_ = master_->opr_overflow_threshold_;
  // 将等待先前center master回复的消息，重新进入发送队列
  map<uint64_t, AgentTaskResult*>::iterator iter = wait_reply_result_map_.begin();
  while(iter != wait_reply_result_map_.end()){
    wait_send_result_map_[iter->first] = iter->second;
    iter->second->last_send_result_time_ = 0;
    ++iter;
  }
  wait_reply_result_map_.clear();

  // 发送报告信息
  CwxMsgBlock* block = NULL;
  dcmd_api::AgentMasterNoticeReply reply;
  // 获取所有命令id
  map<uint64_t, AgentTaskCmd*>::iterator subtask_iter = subtask_map_.begin();
  while(subtask_iter != subtask_map_.end()) {
    *reply.add_cmd()= subtask_iter->second->cmd_.cmd();
    ++subtask_iter;
  }
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to serialize master-change reply, center:%s  conn_id:%d",
      master_->host_name_.c_str(),
      master_->conn_id_));
    return -1; // 关闭连接
  }
  CwxMsgHead head(0,
    0,
    dcmd_api::MTYPE_CENTER_MASTER_NOTICE_R,
    msg->event().getMsgHeader().getTaskId(),
    proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!block) {
    CWX_ERROR(("Failure to pack master-change reply package for no memory"));
    exit(0);
  }
  block->send_ctrl().setConnId(master_->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)) {
    CWX_ERROR(("Failure to send master notice reply to center:%s  conn_id:%d",
      master_->host_name_.c_str(),
      master_->conn_id_));
    CwxMsgBlockAlloc::free(block);
    return -1; // 关闭连接
  }
  return 0;
}

bool DcmdAgentApp::PrepareOprRunEnv(AgentOprCmd* opr_cmd, string& err_msg) {
  FILE* fd = NULL;
  string script_file;
  string script_sh_file;
  string out_file;
  bool is_success = false;
  char str_tmp[64];
  do{
    GetOprRunScriptFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, script_file);
    GetOprRunScriptShellFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, script_sh_file);
    GetOprOuputFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, out_file);
    CWX_DEBUG(("Opr cmd: shell file:%s for opr:%s  index=%s",
      script_sh_file.c_str(),
      opr_cmd->cmd_.name().c_str(),
      CwxCommon::toString(opr_cmd->agent_opr_id_, str_tmp, 0)));
    fd = fopen(script_sh_file.c_str(), "w+");
    if (!fd) {
      CwxCommon::snprintf(err_2k_, 2047, "Failure to open opr script shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fprintf(fd, "#!/bin/sh\n") < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write opr script shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    // 输出环境变量
    int arg_num = opr_cmd->cmd_.args_size();
    string key;
    string value;
    int i = 0;
    for (i=0; i<arg_num; i++) {
      key = opr_cmd->cmd_.args(i).key();
      value = opr_cmd->cmd_.args(i).value();
      dcmd_remove_spam(key);
      dcmd_remove_spam(value);
      if (fprintf(fd, "export %s%s='%s'\n", kAgentOprArgPrex, key.c_str(), value.c_str()) < 0) {
        CwxCommon::snprintf(err_2k_, 2047, "Failure to write script shell file:%s, errno=%d",
          script_sh_file.c_str(), errno);
        err_msg = err_2k_;
        break;
      }
    }
    if (i < arg_num) break;
    //重定向信息
/*    if (fprintf(fd, "exec  1>>%s 2>>%s\n", out_file.c_str(), out_file.c_str()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write script shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }*/
    //执行exec 文件
    if (fprintf(fd, "stdbuf -i0 -o0 -e0 %s>%s 2>&1;\n", script_file.c_str(), out_file.c_str()) < 0){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write script shell file:%s, errno=%d",
        script_sh_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    //关闭fd
    if (fd) fclose(fd);
    fd = NULL;
    //输出操作指令的exec文件
    if (!opr_cmd->cmd_.script().length()){
      CwxCommon::snprintf(err_2k_, 2047, "opr cmd's script is empty.");
      err_msg = err_2k_;
      break;
    }
    fd = fopen(script_file.c_str(), "w+");
    if (!fd){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to open opr script file:%s, errno=%d",
        script_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    if (fwrite(opr_cmd->cmd_.script().c_str(), 1, opr_cmd->cmd_.script().length(), fd) != opr_cmd->cmd_.script().length()){
      CwxCommon::snprintf(err_2k_, 2047, "Failure to write opr script file:%s, errno=%d",
        script_file.c_str(), errno);
      err_msg = err_2k_;
      break;
    }
    is_success = true;
  }while(0);
  if (fd) fclose(fd);
  fd = NULL;
  if (is_success){// 修改shell文件的权限
    if (0 != chmod(script_sh_file.c_str(), S_IRUSR|S_IWUSR|S_IXUSR|S_IRGRP|S_IWGRP|S_IXGRP|S_IROTH|S_IWOTH|S_IXOTH)){
      CwxCommon::snprintf(err_2k_, 2047, 
        "Failure to set 'rwx-rwx-rwx' permissions for shell[%s], errno=%d.",
        script_sh_file.c_str(), errno);
      return false;
    }
    if (0 != chmod(script_file.c_str(), S_IRUSR|S_IWUSR|S_IXUSR|S_IRGRP|S_IWGRP|S_IXGRP|S_IROTH|S_IWOTH|S_IXOTH)){
      CwxCommon::snprintf(err_2k_, 2047,
        "Failure to set 'rwx-rwx-rwx' permissions for oprcmd-exec[%s], errno=%d.",
        script_file.c_str(), errno);
      return false;
    }
  }
  return is_success;
}

bool DcmdAgentApp::ExecOprCmd(AgentOprCmd* opr_cmd, string& err_msg, DcmdProcess*& process)
{
  string script_file;
  GetOprRunScriptShellFile(opr_cmd->cmd_.name(), opr_cmd->agent_opr_id_, script_file);
  process = new DcmdProcess(script_file);
  if (!process->Run(opr_cmd->cmd_.has_run_user() && opr_cmd->cmd_.run_user().length()?opr_cmd->cmd_.run_user().c_str():NULL,
    NULL,
    NULL,
    &err_msg))
  {
    delete process;
    process = NULL;
    return false;
  }
  return true;
}

int DcmdAgentApp::ReplyOprCmd(AgentCenter* center,
  uint32_t msg_task_id,
  bool is_success,
  int   status,
  char const* result,
  char const* err_msg)
{
  CwxMsgBlock* block = NULL;
  dcmd_api::AgentOprCmdReply reply;
  reply.set_state(is_success?dcmd_api::DCMD_STATE_SUCCESS:dcmd_api::DCMD_STATE_FAILED);
  reply.set_result(result?result:"", result?strlen(result):0);
  reply.set_err(err_msg?err_msg:"", err_msg?strlen(err_msg):0);
  reply.set_status(status);
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack opr reply message."));
    return -1; // 关闭连接
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_OPR_CMD_R, msg_task_id, proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!block){
    CWX_ERROR(("Failure to pack reply cmd msg for no memory"));
    return -1;
  }
  block->send_ctrl().setConnId(center->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)) {
    CWX_ERROR(("Failure to send opr reply msg to center:%s, conn_id:%d",
      center->host_name_.c_str(),
      center->conn_id_));
    CwxMsgBlockAlloc::free(block);
    return -1; // 关闭连接
  }
  return 0;
}

int DcmdAgentApp::SubTaskCmdRecieved(CwxMsgBlock*& msg, AgentCenter* center) {
  // 如果此消息不是来自与master控制中心，则关闭连接
  if (center  != master_){
    CWX_ERROR(("Receive subtask cmd from the slave center: %s, master center is:%s, close it",
      center->host_name_.c_str(),
      master_->host_name_.c_str()));
    return -1;
  }
  // 解析消息
  AgentTaskCmd* cmd = new AgentTaskCmd();
  proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!cmd->cmd_.ParseFromString(proto_str_)) {
    delete cmd;
    snprintf(err_2k_, 2047, "Failure to unpack subtask cmd, center:%s",
      master_->host_name_.c_str());
    CWX_ERROR((err_2k_));
    return -1;
  }
  cmd->msg_taskid_ = msg->event().getMsgHeader().getTaskId();
  cmd->cmd_id_ = strtoull(cmd->cmd_.cmd().c_str(), NULL, 10);
  if (subtask_map_.find(cmd->cmd_id_) != subtask_map_.end()){// 指令存在
    CWX_ERROR(("cmd_id[%s] from %s exists.ignore it.",
      cmd->cmd_.cmd().c_str(),
      center->host_name_.c_str()));
    delete cmd;
  }else{
    subtask_map_[cmd->cmd_id_] = cmd;
    recieved_subtasks_.push_back(cmd);
  }
  CWX_INFO(("Receive command from center:%s, cmd_id=%s, subtask=%s, task_id=%d",
    center->host_name_.c_str(),
    cmd->cmd_.cmd().c_str(),
    cmd->cmd_.subtask_id().c_str(),
    cmd->cmd_.task_id().c_str()));

  //回复控制中心
  CwxMsgBlock* block = NULL;
  dcmd_api::AgentTaskCmdReply reply;
  reply.set_cmd(cmd->cmd_.cmd());
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack subtask reply message."));
    return -1; // 关闭连接
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_SUBTASK_CMD_R,
    msg->event().getMsgHeader().getTaskId(), proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!block){
    CWX_ERROR(("Failure to pack heatbeat msg for no memory"));
    return -1;
  }
  block->send_ctrl().setConnId(master_->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)) {
    CWX_ERROR(("Failure to send subtask-reply to center:%s  conn_id:%d, cmd_id=%s",
      master_->host_name_.c_str(),
      master_->conn_id_,
      cmd->cmd_.cmd().c_str()));
    CwxMsgBlockAlloc::free(block);
    return -1; // 关闭连接
  }
  return 0;
}

int DcmdAgentApp::SubTaskResultReply(CwxMsgBlock*& msg, AgentCenter* center){
  // 如果此消息不是来自与master控制中心，则关闭连接
  if (center  != master_){
    CWX_ERROR(("Receive cmd msg from the slave center: %s, master center is:%s, close it",
      center->host_name_.c_str(),
      master_->host_name_.c_str()));
    return -1;
  }
  // 解析消息
  dcmd_api::AgentTaskResultReply result_reply;
  proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!result_reply.ParseFromString(proto_str_)) {
    snprintf(err_2k_, 2047, "Failure to unpack subtask-result reply, center:%s",
      master_->host_name_.c_str());
    CWX_ERROR((err_2k_));
    return -1;
  }
  // 将消息从列表中删除
  uint64_t cmd_id = strtoull(result_reply.cmd().c_str(), NULL, 10);
  map<uint64_t, AgentTaskResult*>::iterator iter = wait_reply_result_map_.find(cmd_id);
  if (iter != wait_reply_result_map_.end()){
    CWX_INFO(("Receive result confirm for cmd:%s from center:%s",
      result_reply.cmd().c_str(),
      center->host_name_.c_str()));
    //删除doing-task文件
    removeDoingTask(iter->second->result_.subtask_id());
    //删除输出文件
    CwxFile::rmFile(iter->second->result_file_.c_str());
    delete iter->second;
    wait_reply_result_map_.erase(iter);
    map<uint64_t, AgentTaskCmd*>::iterator subtask_iter = subtask_map_.find(cmd_id);
    CWX_ASSERT(subtask_iter != subtask_map_.end());
    delete subtask_iter->second;
    subtask_map_.erase(subtask_iter);
  }else{
    CWX_INFO(("Receive result confirm for cmd:%s from center:%s , but it doesn't exist. close connection.",
      result_reply.cmd().c_str(),
      center->host_name_.c_str()));
    return -1;
  }
  return 0;
}

int DcmdAgentApp::OprCmdRecieved(CwxMsgBlock*& msg, AgentCenter* center) {
  if (opr_cmd_map_.size() > opr_overflow_threshold_){// 待处理的命令太多
    sprintf(err_2k_, "Too many queuing opr cmd, max=%d", opr_overflow_threshold_);
    CWX_INFO((err_2k_));
    return ReplyOprCmd(center,
      msg->event().getMsgHeader().getTaskId(),
      false,
      0,
      "",
      err_2k_);
  }
  // 解析消息
  AgentOprCmd* opr_cmd = new AgentOprCmd();
  proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!opr_cmd->cmd_.ParseFromString(proto_str_)) {
    snprintf(err_2k_, 2047, "Failure to unpack opr-cmd, center:%s",
      center->host_name_.c_str());
    CWX_ERROR((err_2k_));
    return -1;
  }
  opr_cmd->center_ = center;
  opr_cmd->begin_time_ = time(NULL);
  opr_cmd->msg_taskid_ = msg->event().getMsgHeader().getTaskId();
  opr_cmd->opr_id_ = strtoull(opr_cmd->cmd_.opr_id().c_str(), NULL, 10);
  next_opr_cmd_id_++;
  while(opr_cmd_map_.find(next_opr_cmd_id_) != opr_cmd_map_.end()){
    next_opr_cmd_id_++;
  }
  opr_cmd->agent_opr_id_ = next_opr_cmd_id_;
  opr_cmd->processor_ = NULL;
  opr_cmd_map_[opr_cmd->agent_opr_id_ ] = opr_cmd;
  CWX_INFO(("Recv opr command from center:%s, opr_cmd_id =%s  ui_name=%s  file=%s",
    center->host_name_.c_str(),
    opr_cmd->cmd_.opr_id().c_str(),
    opr_cmd->cmd_.run_user().c_str(),
    opr_cmd->cmd_.script().c_str()));
  return 0;
}

int DcmdAgentApp::FetchTaskOutputResultRecieved(CwxMsgBlock*& msg, AgentCenter* center) {
  // 解析消息
  dcmd_api::AgentTaskOutput  recv;
  dcmd_api::AgentTaskOutputReply reply;
  proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!recv.ParseFromString(proto_str_)) {
    // 解析失败认为是通信错误，关闭连接
    CWX_ERROR(("Failure to unpack subtask output cmd, center:%s",
      center->host_name_.c_str()));
    return -1;
  }
  string file_name;
  // 获取任务指令输出文件
  GetTaskOutputFile(file_name, recv.subtask_id());
  CWX_DEBUG(("Receive subtask output command from center:%s, subtask-id=%s, offset=%u, file=%s ",
    center->host_name_.c_str(),
    recv.subtask_id().c_str(),
    recv.offset(),
    file_name.c_str()));
  int fd = -1;
  uint32_t data_len = center->max_package_size_ * 1024 * 1024;
  char* data_buf = GetBuf(data_len);
  ssize_t ret = 0;
  do{
    fd = ::open(file_name.c_str(), O_RDONLY);
    if (-1 == fd){
      reply.set_err("Task result doesn't exist.");
      reply.set_result(reply.err().c_str(), reply.err().length());
      reply.set_state(dcmd_api::DCMD_STATE_FAILED);
      reply.set_offset(0);
      break;
    }
    ret = ::pread(fd, data_buf, data_len, recv.offset());
    if (0 > ret){
      CwxCommon::snprintf(data_buf, data_len, "Failure to read subtask file:%s, errno=%d",
        file_name.c_str(), errno);
      reply.set_err(data_buf);
      reply.set_result(data_buf, strlen(data_buf));
      reply.set_state(dcmd_api::DCMD_STATE_FAILED);
      reply.set_offset(0);
      ::close(fd);
      break;
    }
    ::close(fd);
    reply.set_err("");
    reply.set_result(data_buf, ret);
    reply.set_state(dcmd_api::DCMD_STATE_SUCCESS);
    reply.set_offset(ret + recv.offset());
  }while(0);

  CwxMsgBlock* block=NULL;
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack subtask output reply message."));
    return -1; // 关闭连接
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_AGENT_SUBTASK_OUTPUT_R,
    msg->event().getMsgHeader().getTaskId(), proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!block){
    CWX_ERROR(("Failure to pack subtask result msg for no memory"));
    return -1;
  }
  block->send_ctrl().setConnId(center->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)){
    CWX_ERROR(("Failure to send subtask result to center:%s  conn_id:%d",
      center->host_name_.c_str(),
      center->conn_id_));
    CwxMsgBlockAlloc::free(block);
    return -1; // 关闭连接
  }
  return 0;
}

int DcmdAgentApp::GetRunTaskRecieved(CwxMsgBlock*& msg, AgentCenter* center) {
  // 解析消息
  dcmd_api::AgentRunningTask  recv;
  dcmd_api::AgentRunningTaskReply reply;
  proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!recv.ParseFromString(proto_str_)) {
    // 解析失败认为是通信错误，关闭连接
    CWX_ERROR(("Failure to unpack get run task cmd, center:%s ",
      center->host_name_.c_str()));
    return -1;
  }
  CWX_DEBUG(("Receive get-run-task-command from center:%s", center->host_name_.c_str()));
  dcmd_api::SubTaskInfo* subtask = NULL;

  map<string, AgentSvrPoolObj*>::iterator iter = svr_pool_map_.begin();
  list<AgentTaskCmd*>::iterator subtask_iter;
  while(iter != svr_pool_map_.end()){
    if (recv.has_app_name() && recv.app_name().length()) {
      if (recv.app_name() != iter->second->app_name_) {
        iter++;
        continue;
      }
      if (recv.has_svr_name() && recv.svr_name().length()) {
        if (recv.svr_name() != iter->second->svr_name_) {
          iter++;
          continue;
        }
        if (recv.has_svr_pool() && recv.svr_pool().length()) {
          if (recv.svr_pool() != iter->second->svr_pool_name_) {
            iter++;
            continue;
          }
        }
      }
    }
    if (iter->second->running_cmd_) {
      subtask = reply.add_result();
      if (subtask) {
        subtask->set_app_name(iter->second->app_name_);
        subtask->set_svr_name(iter->second->svr_name_);
        subtask->set_svr_pool(iter->second->svr_pool_name_);
        subtask->set_task_cmd(iter->second->running_cmd_->cmd_.task_cmd());
        subtask->set_task_id(iter->second->running_cmd_->cmd_.task_id());
        subtask->set_subtask_id(iter->second->running_cmd_->cmd_.subtask_id());
        subtask->set_cmd_id(iter->second->running_cmd_->cmd_.cmd());
      }
    }
    subtask_iter = iter->second->cmds_.begin();
    while(subtask_iter != iter->second->cmds_.end()) {
      subtask = reply.add_result();
      if (subtask) {
        subtask->set_app_name(iter->second->app_name_);
        subtask->set_svr_name(iter->second->svr_name_);
        subtask->set_svr_pool(iter->second->svr_pool_name_);
        subtask->set_task_cmd((*subtask_iter)->cmd_.task_cmd());
        subtask->set_task_id((*subtask_iter)->cmd_.task_id());
        subtask->set_subtask_id((*subtask_iter)->cmd_.subtask_id());
        subtask->set_cmd_id((*subtask_iter)->cmd_.cmd());
      }
      ++ subtask_iter;
    }
    ++iter;
  }
  reply.set_err("");
  reply.set_state(dcmd_api::DCMD_STATE_SUCCESS);
  CwxMsgBlock* block=NULL;
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack fetch subtask list reply message."));
    return -1; // 关闭连接
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_AGENT_RUNNING_TASK_R,
    msg->event().getMsgHeader().getTaskId(), proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());

  if (!block) {
    snprintf(err_2k_, 2047, "Failure to pack get-run-task-reply, center:%s",
      center->host_name_.c_str());
    CWX_ERROR((err_2k_));
    return -1;
  }
  block->send_ctrl().setConnId(center->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)){
    CWX_ERROR(("Failure to send get run task result to center:%s  conn_id:%d",
      center->host_name_.c_str(),
      center->conn_id_));
    CwxMsgBlockAlloc::free(block);
    return -1; // 关闭连接
  }
  return 0;
}

int DcmdAgentApp::GetRunOprRecieved(CwxMsgBlock*& msg, AgentCenter* center) {
  // 解析消息
  dcmd_api::AgentRunningOpr  recv;
  dcmd_api::AgentRunningOprReply reply;
  proto_str_.assign(msg->rd_ptr(), msg->length());
  if (!recv.ParseFromString(proto_str_)) {
    // 解析失败认为是通信错误，关闭连接
    CWX_ERROR(("Failure to unpack get run opr cmd, center:%s ",
      center->host_name_.c_str()));
    return -1;
  }
  CWX_INFO(("Receive get-run-opr-command from center:%s",
    center->host_name_.c_str()));
  map<uint64_t, AgentOprCmd*>::iterator iter = opr_cmd_map_.begin();
  dcmd_api::OprInfo* opr_info = NULL;
  string start_time;
  while(iter != opr_cmd_map_.end()){
    opr_info = reply.add_result();
    if (opr_info) {
      opr_info->set_name(iter->second->cmd_.name());
      CwxDate::getDate(iter->second->begin_time_, start_time);
      opr_info->set_start_time(start_time);
      opr_info->set_running_second(time(NULL) - iter->second->begin_time_);
    }
    iter++;
  }
  reply.set_err("");
  reply.set_state(dcmd_api::DCMD_STATE_SUCCESS);

  CwxMsgBlock* block=NULL;
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack fetch opr cmd list reply message."));
    return -1; // 关闭连接
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_CENTER_AGENT_RUNNING_OPR_R,
    msg->event().getMsgHeader().getTaskId(), proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!block) {
    snprintf(err_2k_, 2047, "Failure to pack get-run-opr-reply, center:%s",
      center->host_name_.c_str());
    CWX_ERROR((err_2k_));
    return -1;
  }
  block->send_ctrl().setConnId(center->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)){
    CWX_ERROR(("Failure to send get-run-opr-reply to center:%s  conn_id:%d",
      center->host_name_.c_str(),
      center->conn_id_));
    CwxMsgBlockAlloc::free(block);
    return -1; // 关闭连接
  }
  return 0;
}

void DcmdAgentApp::CheckSubTaskProcess(AgentSvrPoolObj* svr_pool_obj) {
  bool is_success = false;
  string out_process;
  string err_msg;
  bool is_finish = false;
  if (!master_) return ;
  if (svr_pool_obj->running_cmd_->last_check_process_time_ + 1 > (uint32_t)time(NULL)) return;
  // 获取进度信息
  LoadSubTaskResult(svr_pool_obj->running_cmd_->cmd_.subtask_id(),
    out_process,
    is_success,
    err_msg,
    is_finish,
    true);
  if (!out_process.length() || (out_process == svr_pool_obj->running_cmd_->last_check_process_))
    return;
  // report process
  CwxMsgBlock* block = NULL;
  dcmd_api::AgentSubTaskProcess reply;
  reply.set_task_id(svr_pool_obj->running_cmd_->cmd_.task_id());
  reply.set_subtask_id(svr_pool_obj->running_cmd_->cmd_.subtask_id());
  reply.set_process(out_process);
  if (!reply.SerializeToString(&proto_str_)) {
    CWX_ERROR(("Failure to pack process reply message."));
    return;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_AGENT_SUBTASK_PROCESS,
    0, proto_str_.length());
  block = CwxMsgBlockAlloc::pack(head, proto_str_.c_str(), proto_str_.length());
  if (!block){
    CWX_ERROR(("Failure to pack subtask process msg for no memory"));
    return;
  }
  block->send_ctrl().setConnId(master_->conn_id_);
  block->send_ctrl().setSvrId(SVR_TYPE_CENTER);
  block->send_ctrl().setHostId(0);
  block->send_ctrl().setMsgAttr(CwxMsgSendCtrl::NONE);
  if (-1 == sendMsgByConn(block)){
    CWX_ERROR(("Failure to send subtask process msg to center:%s, conn_id:%d",
      master_->host_name_.c_str(),
      master_->conn_id_));
    CwxMsgBlockAlloc::free(block);
    return;
  }
  // 修改svr的process
  svr_pool_obj->running_cmd_->last_check_process_ = out_process;
  svr_pool_obj->running_cmd_->last_check_process_time_ = time(NULL);
}

void DcmdAgentApp::LoadSubTaskResult(string const& subtask_id,
  string& out_process,
  bool& is_success,
  string& err_msg,
  bool& is_finish,
  bool is_process_only)
{
  string out_filename;
  string out_data;
  out_process.erase();
  is_success = false;
  is_finish = false;
  err_msg.erase();
  // 获取文件
  GetTaskResultFile(subtask_id, out_filename);
  if (!CwxFile::isFile(out_filename.c_str())){
    err_msg = "No subtask-result file:";
    err_msg += out_filename;
    return;
  }
  // 获取文件
  if (!CwxFile::readTxtFile(out_filename, out_data)){
    snprintf(err_2k_, 2047, "Failure to read result file:%s, errno=%d",
      out_filename.c_str(), errno);
    err_msg = err_2k_;
    CWX_ERROR((err_2k_));
    return;
  }
  list<pair<string, string> > items;
  pair<string, string> item;
  CwxCommon::split(out_data, items, '\n');
  // 获取process
  if (!findLastKey(items, kAgentTaskResultKeyProcess, item)){
    out_process = "";
  } else {
    out_process = item.second;
  }
  if (is_process_only) {
    is_success = true;
    return;
  }
  // 获取状态
  if (!findLastKey(items, kAgentTaskResultKeyState, item)){
    CwxCommon::snprintf(err_2k_, 2047, "No [%s] key in result file:%s",
      kAgentTaskResultKeyState, out_filename.c_str());
    err_msg = err_2k_;
    return;
  }
  is_finish = true;
  if (item.second != kAgentTaskResultKeyStateSuccess){
    is_success = false;
  }else{
    is_success = true;
  }
  if (!is_success){
    // 获取err信息
    if (!findLastKey(items, kAgentTaskResultKeyErr, item)){
      CwxCommon::snprintf(err_2k_, 2047, "No [%s] key in result file:%s", 
        kAgentTaskResultKeyErr, out_filename.c_str());
      err_msg = err_2k_;
      return;
    }
    err_msg = item.second;
  }
}

//获取正在做的task
bool DcmdAgentApp::loadDoingTasks() {
  //load 全部的subtask
  list<string> files;
  string subtask_path;
  string subtask_file;
  GetDoingTaskPath(subtask_path);
  if (!CwxFile::getDirFile(subtask_path, files)) {
    CWX_ERROR(("Failure to load path file, path:%s, errno", subtask_path.c_str(), errno));
    return false;
  }
  list<string>::iterator iter = files.begin();
  AgentDoingTaskInfo task_info;
  string out_process;
  bool is_success = false;
  string err_msg;
  bool is_finish = false;
  uint64_t cmd_id=0;
  AgentTaskResult* task_result=NULL;
  while(iter != files.end()) {
    if (!loadDoingTask(*iter, task_info)) {
      removeDoingTask(*iter);
    } else {
      // load 进程的process
      if (CwxFile::isFile(task_info.task_result_file_.c_str())) {
        LoadSubTaskResult(task_info.subtask_id_, out_process, is_success, err_msg, is_finish, false);
        if (is_finish) {
          //将任务结果放到等待发送map
          cmd_id = strtoull(task_info.cmd_id_.c_str(), NULL, 10);
          task_result = new AgentTaskResult;
          task_result->msg_taskid_ = task_info.msg_task_id_;
          task_result->cmd_id_ = cmd_id;
          task_result->last_send_result_time_ = 0;
          task_result->result_.set_cmd(task_info.cmd_id_);
          task_result->result_.set_task_id(task_info.task_id_);
          task_result->result_.set_subtask_id(task_info.subtask_id_);
          task_result->result_.set_success(is_success);
          task_result->result_.set_err(err_msg);
          task_result->result_.set_process(out_process);
          task_result->result_file_ = task_info.task_result_file_;
          wait_send_result_map_[cmd_id] = task_result;
          //插入dummy的TaskCmd
          subtask_map_[cmd_id] = new AgentTaskCmd;
        } else {
          removeDoingTask(*iter);
        }
      } else {
        removeDoingTask(*iter);
      }
    }
    iter++;
  }
  return true;
}
//获取正在做的task的信息
bool DcmdAgentApp::loadDoingTask(string const& subtask_id, AgentDoingTaskInfo& task_info) {
  string subtask_file;
  GetDoingTaskFile(subtask_file, subtask_id);
  if (!CwxFile::isFile(subtask_file.c_str())) {
    CWX_ERROR(("Doing-task doesn't exist, subtask_id[%s], file:%s", subtask_id.c_str(), subtask_file.c_str()));
    return false;
  }
  string file_data;
  if (!CwxFile::readTxtFile(subtask_file.c_str(), file_data)) {
    CWX_ERROR(("Failure to read doing-task, subtask_id[%s], file:%s", subtask_id.c_str(), subtask_file.c_str()));
    return false;
  }
  return task_info.load(file_data);
}
//删除正在做的task的信息
bool DcmdAgentApp::removeDoingTask(string const& subtask_id) {
  string subtask_file;
  GetDoingTaskFile(subtask_file, subtask_id);
  return CwxFile::rmFile(subtask_file.c_str());
}
//保存正在做的task的信息
bool DcmdAgentApp::saveDoingTask(AgentDoingTaskInfo const& task_info) {
  string result;
  string subtask_file;
  task_info.dump(result);
  GetDoingTaskFile(subtask_file, task_info.subtask_id_);
  FILE* fd = ::fopen(subtask_file.c_str(), "wb");
  if (!fd) {
    CWX_ERROR(("Failure to open doing-task-file:%s, errno=%d", subtask_file.c_str(), errno));
    return false;
  }
  if (result.length() != ::fwrite(result.c_str(), 1, result.length(), fd)) {
    CWX_ERROR(("Failure to write doing-task-file:%s, errno=%d", subtask_file.c_str(), errno));
    ::fclose(fd);
    return false;
  }
  ::fclose(fd);
  return true;
}

}

