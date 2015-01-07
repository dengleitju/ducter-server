#include <CwxDate.h>
#include "dcmd_center_app.h"

namespace dcmd {
DcmdCenterApp::DcmdCenterApp() {
  admin_mysql_ = NULL;
  task_mysql_ = NULL;
  check_mysql_ = NULL;
  is_master_ = true;
  agent_task_handler_ = NULL;
  agent_opr_handler_ = NULL;
  admin_handler_ = NULL;
  check_handler_ = NULL;
  task_mgr_ = NULL;
  opr_cmd_cache_ = NULL;
  agent_mgr_ = NULL;
  task_thread_pool_ = NULL;
  admin_thread_pool_ = NULL;
  check_thread_pool = NULL;
}
DcmdCenterApp::~DcmdCenterApp() {
  // 在析构函数不释放资源，资源的是否是在destroy函数中。
}
int DcmdCenterApp::init(int argc, char** argv) {
  string err_msg;
  // 首先调用架构的init api
  if (CwxAppFramework::init(argc, argv) == -1) return -1;
  // 检查是否通过-f指定了配置文件，若没有，则采用默认的配置文件
  if ((NULL == this->getConfFile()) || (strlen(this->getConfFile()) == 0)) {
    this->setConfFile("dcmd_center.conf");
  }
  // 加载配置文件，若失败则退出
  if (0 != config_.Init(getConfFile())) {
    CWX_ERROR((config_.err_msg()));
    return -1;
  }
  // 设置运行日志的输出level
  if (config_.common().is_debug_){
    setLogLevel(CwxLogger::LEVEL_ERROR|CwxLogger::LEVEL_INFO|CwxLogger::LEVEL_WARNING|CwxLogger::LEVEL_DEBUG);
  }else{
    setLogLevel(CwxLogger::LEVEL_ERROR|CwxLogger::LEVEL_INFO|CwxLogger::LEVEL_WARNING);
  }
  return 0;
}
int DcmdCenterApp::initRunEnv(){
  // 设置系统的时钟间隔，最小刻度为1ms，此为1s。
  this->setClick(100);//0.1s
  // 设置工作目录
  this->setWorkDir(config_.common().work_home_.c_str());
  // 设置循环运行日志的数量
  this->setLogFileNum(config_.common().log_file_num_);
  // 设置每个日志文件的大小
  this->setLogFileSize(config_.common().log_file_size_ * 1024 * 1024);
  // 调用架构的initRunEnv，使以上设置的参数生效
  if (CwxAppFramework::initRunEnv() == -1 ) return -1;
  // 将加载的配置文件信息输出到日志文件中
  config_.OutputConfig();
  // 设置启动时间
  CwxDate::getDateY4MDHMS2(time(NULL), start_datetime_);
  // set version
  this->setAppVersion(kDcmdCenterVersion);
  // set last modify date
  this->setLastModifyDatetime(kDcmdCenterModifyDate);
  // set compile date
  this->setLastCompileDatetime(CWX_COMPILE_DATE(_BUILD_DATE));
  // 设置服务状态
  this->setAppRunValid(true);
  // 创建admin线程的mysql对象
  CWX_DEBUG(("Init admin mysql connection...."));
  admin_mysql_ = new Mysql();
  if (!admin_mysql_->init()) {// 此必须退出
    CWX_ERROR(("Failure to init admin mysql connect. err=%s",
      admin_mysql_->getErrMsg()));
    return -1;
  }
  if (!ConnectMysql(admin_mysql_, 3)) {
    CWX_ERROR(("Failure to connect to admin mysql, error=%s", admin_mysql_->getErrMsg()));
    // 不能退出，这是常态
  } else {
    admin_mysql_->setAutoCommit(false);
  }
  // 创建task线程的mysql对象
  CWX_DEBUG(("Init task mysql connection...."));
  task_mysql_ = new Mysql();
  if (!task_mysql_->init()) {// 此必须退出
    CWX_ERROR(("Failure to init task mysql connect. err=%s", task_mysql_->getErrMsg()));
    return -1;
  }
  if (!ConnectMysql(task_mysql_, 3)) {
    CWX_ERROR(("Failure to connect to task mysql, error=%s",
      task_mysql_->getErrMsg()));
    // 不能退出，这是常态
  }else{
    task_mysql_->setAutoCommit(false);
  }
  // 创建check master的mysql对象
  CWX_DEBUG(("Init check mysql connection...."));
  check_mysql_ = new Mysql();
  if (!check_mysql_->init()) {// 此必须退出
    CWX_ERROR(("Failure to init check mysql connect. err=%s", check_mysql_->getErrMsg()));
    return -1;
  }
  if (!ConnectMysql(check_mysql_, 3)) {
    CWX_ERROR(("Failure to connect to task mysql, error=%s", check_mysql_->getErrMsg()));
    // 不能退出，这是常态
  }else{
    check_mysql_->setAutoCommit(false);
  }

  // 初始化为非master状态
  is_master_ = false;   
  // 创建来自agent事件的处理handler，此由task线程调用
  agent_task_handler_ = new DcmdCenterH4AgentTask(this);
  // 创建agent opr的 handler，此有admin线程调用
  agent_opr_handler_ = new DcmdCenterH4AgentOpr(this);
  // 创建admin handler，此有admin线程调用
  admin_handler_ = new DcmdCenterH4Admin(this);
  // 创建master check的handler，此有check线程调用
  check_handler_ = new DcmdCenterH4Check(this);
  // 注册事件处理handler
  getCommander().regHandle(SVR_TYPE_AGENT, agent_task_handler_);
  getCommander().regHandle(SVR_TYPE_AGENT_OPR, agent_opr_handler_);
  getCommander().regHandle(SVR_TYPE_ADMIN, admin_handler_);
  getCommander().regHandle(SVR_TYPE_MASTER_CHECK, check_handler_);
  // 创建task管理器对象
  task_mgr_ = new DcmdCenterTaskMgr(this);
  // 创建opr cmd的cache对象
  opr_cmd_cache_ = new DcmdCenterOprCache();
  // 创建agent管理对象
  agent_mgr_ = new DcmdCenterAgentMgr(this);
  // 打开admin监听端口
  CWX_INFO(("Open admin listen: %s:%u",
    config_.common().ui_listen_.getHostName().c_str(),
    config_.common().ui_listen_.getPort()));
  if (0 > this->noticeTcpListen(SVR_TYPE_ADMIN,
    config_.common().ui_listen_.getHostName().c_str(),
    config_.common().ui_listen_.getPort(),
    false))
  {
    CWX_ERROR(("Can't register the admin listen: addr=%s, port=%d",
      config_.common().ui_listen_.getHostName().c_str(),
      config_.common().ui_listen_.getPort()));
    return -1;
  }
  // 打开agent连接监听端口
  CWX_INFO(("Open agent listen: %s:%u",
    config_.common().agent_listen_.getHostName().c_str(),
    config_.common().agent_listen_.getPort()));
  if (0 > this->noticeTcpListen(SVR_TYPE_AGENT,
    config_.common().agent_listen_.getHostName().c_str(),
    config_.common().agent_listen_.getPort(),
    false))
  {
    CWX_ERROR(("Can't register the agent listen: addr=%s, port=%d",
      config_.common().agent_listen_.getHostName().c_str(),
      config_.common().agent_listen_.getPort()));
    return -1;
  }
  // 创建任务线程池
  CWX_INFO(("Start task thread pool...."));
  task_thread_pool_ = new CwxThreadPool(1, &getCommander());
  // 创建线程的tss对象
  CwxTss** pTss = new CwxTss*[1];
  pTss[0] = new DcmdTss();
  ((DcmdTss*)pTss[0])->Init();
  // 启动线程
  if ( 0 != task_thread_pool_->start(pTss)) {
    CWX_ERROR(("Failure to start task thread pool"));
    return -1;
  }
  // 创建admin线程池
  CWX_DEBUG(("Start admin thread pool...."));
  admin_thread_pool_ = new CwxThreadPool(1, &getCommander());
  // 创建线程的tss对象
  pTss = new CwxTss*[1];
  pTss[0] = new DcmdTss();
  ((DcmdTss*)pTss[0])->Init();
  // 启动线程
  if ( 0 != admin_thread_pool_->start(pTss)) {
    CWX_ERROR(("Failure to start admin thread pool"));
    return -1;
  }
  // 创建master check线程池
  CWX_DEBUG(("Start master check thread pool...."));
  check_thread_pool = new CwxThreadPool(1, &getCommander());
  // 创建线程的tss对象
  pTss = new CwxTss*[1];
  pTss[0] = new DcmdTss();
  ((DcmdTss*)pTss[0])->Init();
  // 启动线程
  if ( 0 != check_thread_pool->start(pTss)) {
    CWX_ERROR(("Failure to start master check thread pool"));
    return -1;
  }
  CWX_INFO(("Finish to init environment."));
  return 0;
}
void DcmdCenterApp::onTime(CwxTimeValue const& current) {
  // 调用基类的onTime函数
  CwxAppFramework::onTime(current);
  // 检查超时
  uint32_t now = time(NULL);
  static uint32_t last_admin_timeout_check = now;
  static uint32_t last_task_timeout_check = now;
  static uint32_t last_master_timeout_check = now;
  static uint32_t base_time = 0;
  bool is_clock_back = IsClockBack(base_time, now);
  // 检查opr 指令超时
  if (is_clock_back || (last_admin_timeout_check  < now)) {
    last_admin_timeout_check = now;
    if (admin_thread_pool_) {
      CwxMsgBlock* block = CwxMsgBlockAlloc::malloc(0);
      block->event().setSvrId(SVR_TYPE_ADMIN);
      block->event().setEvent(CwxEventInfo::TIMEOUT_CHECK);
      // 将超时检查事件，放入事件队列
      admin_thread_pool_->append(block);
    }
  }
  // 检查agent的mysql的状态、新指令及非法agent
  if (is_clock_back || (last_task_timeout_check < now)) {
    last_task_timeout_check = now;        
    // 检测agent的心跳，若没有心跳的将关闭
    agent_mgr_->CheckHeatbeat();
    // task线程的定时处理函数，数据库连接、新指令的检查
    if (task_thread_pool_) {
      CwxMsgBlock* block = CwxMsgBlockAlloc::malloc(0);
      block->event().setSvrId(SVR_TYPE_AGENT);
      block->event().setEvent(CwxEventInfo::TIMEOUT_CHECK);
      //将超时检查事件，放入事件队列
      task_thread_pool_->append(block);
    }
  }
  // 检测测试是否为master
  if (is_clock_back || (last_master_timeout_check + kCenterMasterCheckSecond < now)) {
    last_master_timeout_check = now;
    if (check_thread_pool) {
      CwxMsgBlock* block = CwxMsgBlockAlloc::malloc(0);
      block->event().setSvrId(SVR_TYPE_MASTER_CHECK);
      block->event().setEvent(CwxEventInfo::TIMEOUT_CHECK);
      //将超时检查事件，放入事件队列
      check_thread_pool->append(block);
    }
  }
}
void DcmdCenterApp::onSignal(int signum){
    switch(signum){
    case SIGQUIT: 
        // 若监控进程通知退出，则推出
        CWX_INFO(("Receive exit signal, exit right now."));
        this->stop();
        break;
    default:
        ///其他信号，全部忽略
        CWX_INFO(("Receive signal=%d, ignore it.", signum));
        break;
    }
}
int DcmdCenterApp::onConnCreated(CwxAppHandler4Msg& conn, bool& , bool& ) {
  // 获取连接的ip
  char conn_ip[128];
  memset(conn_ip, 0x00, 128);
  conn.getRemoteAddr(conn_ip, 128);
  if (!conn_ip[0]) {
    CWX_ERROR(("Failure to get connection's remote ip, conn_id=%u. close it.",
      conn.getConnInfo().getConnId()));
    return -1;
  }
  if (SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()) {
    // 检测是否为非法的agent连接
    if (agent_mgr_->IsInvalidConnIp(string(conn_ip))){
      CWX_ERROR(("Conn ip[%s] is illegal agent ip, close it.", conn_ip));
      return -1;
    }
    // 将连接注册到agent的管理对象，一定不存在否则是bug
    bool bRet = agent_mgr_->AddConn(conn.getConnInfo().getConnId(), conn_ip);
    CWX_INFO(("Agent for connect_ip:%s is connected", conn_ip));
    if (!bRet){
      CWX_ERROR(("Connect ip:%s exists, it's a big wrong. i need exit.", conn_ip));
      ::exit(0);
    }
  } else if (SVR_TYPE_ADMIN == conn.getConnInfo().getSvrId()) {
    if (config_.common().allow_ui_ips_.size()) {
      if (config_.common().allow_ui_ips_.find(string(conn_ip)) == config_.common().allow_ui_ips_.end()) {
        char const* ptr = strrchr(conn_ip, '.');
        if (!ptr) {
          CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
          return -1;
        }
        conn_ip[ptr-conn_ip] = 0;
        if (config_.common().allow_ui_ips_.find(string(conn_ip)) == config_.common().allow_ui_ips_.end()) {
          CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
          return -1;
        }
      }
    }
  }
  return 0;
}
int DcmdCenterApp::onConnClosed(CwxAppHandler4Msg& conn) {
  if (SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()) {
    string conn_ip = "";
    string agent_ip = "";
    agent_mgr_->GetConnIp(conn.getConnInfo().getConnId(), conn_ip);
    if (!agent_mgr_->GetAgentIp(conn.getConnInfo().getConnId(), agent_ip))
      agent_ip = "";
    CWX_INFO(("Agent[%s] for connect_ip[%s] is closed",
      agent_ip.c_str(), conn_ip.length()?conn_ip.c_str():"unknown"));
    // 从agent管理器中关闭此连接
    bool bool_ret = agent_mgr_->RemoveConn(conn.getConnInfo().getConnId());
    if (!bool_ret) {
      // 必须存在，否则是个bug
      CWX_ERROR(("Agent connect is closed, it doesn't exist in agent mgr, i will abord"));
      ::exit(0);
    }
    // agent连接关闭的事件
    CwxMsgBlock* block = CwxMsgBlockAlloc::malloc(0);
    block->event().setHostId(conn.getConnInfo().getHostId());
    block->event().setConnId(conn.getConnInfo().getConnId());
    // 设置事件类型
    block->event().setEvent(CwxEventInfo::CONN_CLOSED);
    // 放到agent的队列中
    if (agent_ip.length()) {
      CwxMsgBlock* agent_block = CwxMsgBlockAlloc::clone(block);
      agent_block->event().setSvrId(conn.getConnInfo().getSvrId());
      char* ip = strdup(agent_ip.c_str());
      agent_block->event().setConnUserData(ip);
      task_thread_pool_->append(agent_block);
    }
    // 放到admin的队列中，防止task_thread_pool_阻塞而影响opr指令的执行
    block->event().setSvrId(SVR_TYPE_AGENT_OPR);
    admin_thread_pool_->append(block);
  }
  // 对于admin的连接无视
  return 0;
}
int DcmdCenterApp::onRecvMsg(CwxMsgBlock* msg, CwxAppHandler4Msg& conn,
                      CwxMsgHead const& header,  bool& )
{
  // 如果是心跳信号，则直接由主线程处理
  if ((SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()) &&
    (header.getMsgType() == dcmd_api::MTYPE_AGENT_HEATBEAT))
  {
    string host_name;
    host_name.assign(msg->rd_ptr(), msg->length());
    if (msg) CwxMsgBlockAlloc::free(msg);
    agent_mgr_->Heatbeat(conn.getConnInfo().getConnId(), host_name);
    return 0;
  }
  // 消息必须不能为空
  if (!msg){
    string conn_ip="";
    agent_mgr_->GetConnIp(conn.getConnInfo().getConnId(), conn_ip);
    CWX_ERROR(("Receive a empty msg from conn:%s, close it",
      conn_ip.length()?conn_ip.c_str():"unknown"));
    return -1;
  }
  msg->event().setEvent(CwxEventInfo::RECV_MSG);
  msg->event().setMsgHeader(header);
  msg->event().setSvrId(conn.getConnInfo().getSvrId());
  msg->event().setHostId(conn.getConnInfo().getHostId());
  msg->event().setConnId(conn.getConnInfo().getConnId());
  if (SVR_TYPE_ADMIN == conn.getConnInfo().getSvrId()) { // ui来的消息
    if (msg->event().getMsgHeader().getMsgType() == dcmd_api::MTYPE_UI_EXEC_TASK) {
      msg->event().setSvrId(SVR_TYPE_AGENT);
      task_thread_pool_->append(msg);
    } else {
      admin_thread_pool_->append(msg);
    }
    return 0;
  }else if (SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()){
    if ((header.getMsgType() == dcmd_api::MTYPE_CENTER_OPR_CMD_R) ||
      (header.getMsgType() == dcmd_api::MTYPE_CENTER_AGENT_SUBTASK_OUTPUT_R) ||
      (header.getMsgType() == dcmd_api::MTYPE_CENTER_AGENT_RUNNING_TASK_R) ||
      (header.getMsgType() == dcmd_api::MTYPE_CENTER_AGENT_RUNNING_OPR_R))
    {
      // 消息由admin线程处理
      msg->event().setSvrId(SVR_TYPE_AGENT_OPR);
      admin_thread_pool_->append(msg);
    } else {
      task_thread_pool_->append(msg);
    }
    return 0;
  }
  if (msg) CwxMsgBlockAlloc::free(msg);
  CWX_ERROR(("Receive msg from unknown service type:%d", conn.getConnInfo().getSvrId()));
  return -1;
}
void DcmdCenterApp::onFailSendMsg(CwxMsgBlock*& msg) {
  ///只有opr及task result的指令才需要。
  if (SVR_TYPE_AGENT_OPR == msg->event().getSvrId()){
    msg->event().setHostId(msg->send_ctrl().getHostId());
    msg->event().setConnId(msg->send_ctrl().getConnId());
    msg->event().setEvent(CwxEventInfo::FAIL_SEND_MSG);
    admin_thread_pool_->append(msg);
    msg = NULL;
  }else{
    CWX_ERROR(("Invalid fail-send-msg, svr_id=%d", msg->event().getSvrId()));
  }
}
CWX_UINT32 DcmdCenterApp::onEndSendMsg(CwxMsgBlock*& msg, CwxAppHandler4Msg& conn) {
  // 只有Opr及task result的指令，才需要。
  if (SVR_TYPE_AGENT_OPR == msg->event().getSvrId()){
    msg->event().setHostId(conn.getConnInfo().getHostId());
    msg->event().setConnId(conn.getConnInfo().getConnId());
    msg->event().setEvent(CwxEventInfo::END_SEND_MSG);
    admin_thread_pool_->append(msg);
    msg = NULL;
  }else{
    CWX_ERROR(("Invalid end-send-msg, svr_id=%d", msg->event().getSvrId()));
  }
  return 0;
}

void DcmdCenterApp::destroy() {
  ///停止线程池
  if (task_thread_pool_) task_thread_pool_->stop();
  if (admin_thread_pool_) admin_thread_pool_->stop();
  if (check_thread_pool) check_thread_pool->stop();
  if (task_thread_pool_) delete task_thread_pool_;
  task_thread_pool_ = NULL;
  if (admin_thread_pool_) delete admin_thread_pool_;
  admin_thread_pool_ = NULL;
  if (check_thread_pool) delete check_thread_pool;
  check_thread_pool = NULL;
  //关闭mysql
  if (admin_mysql_) {
    admin_mysql_->disconnect();
    delete admin_mysql_;
    admin_mysql_ = NULL;
  }
  if (task_mysql_) {
    task_mysql_->disconnect();
    delete task_mysql_;
    task_mysql_ = NULL;
  }
  if (check_mysql_) {
    check_mysql_->disconnect();
    delete check_mysql_;
    check_mysql_ = NULL;
  }
  // 删除handler
  if (agent_task_handler_) delete agent_task_handler_;
  agent_task_handler_ = NULL;
  if (agent_opr_handler_) delete agent_opr_handler_;
  agent_opr_handler_ = NULL;
  if (admin_handler_) delete admin_handler_;
  admin_handler_ = NULL;
  if (check_handler_) delete check_handler_;
  check_handler_ = NULL;
  // 删除任务管理对象
  if (task_mgr_) delete task_mgr_;
  task_mgr_ = NULL;
  // 删除opr的cache对象
  if (opr_cmd_cache_) delete opr_cmd_cache_;
  opr_cmd_cache_ = NULL;
  // 删除连接管理器对象
  if (agent_mgr_) delete agent_mgr_;
  agent_mgr_ = NULL;

  CwxAppFramework::destroy();
}
bool DcmdCenterApp::ConnectMysql(Mysql* my, uint32_t timeout) {
  if (my->IsConnected()) return true;
  my->setOption(MYSQL_OPT_CONNECT_TIMEOUT, (const char *)&timeout);
  if (!my->connect(config().mysql().server_.c_str(),
    config().mysql().user_.c_str(),
    config().mysql().passwd_.c_str(),
    config().mysql().db_name_.c_str(),
    config().mysql().port_))
  {
    return false;
  }
  return true;
}
bool DcmdCenterApp::CheckMysql(Mysql* my) {
  if (!my->ping()) {
    my->disconnect();
    if (ConnectMysql(my, kDcmdMysqlConnectTimeout)) {
      my->setAutoCommit(false);
      return true;
    }
    return false;
  }
  return true;
}
bool DcmdCenterApp::IsClockBack(uint32_t& last_time, uint32_t now) {
  if (last_time > now){
    last_time = now;
    return true;
  }
  last_time = now;
  return false;
}
}  // dcmd

