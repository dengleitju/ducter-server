#include "dcmd_center_config.h"
#include <CwxLogger.h>

namespace dcmd {
int DcmdCenterConf::Init(string const& conf_file) {
  string value;
  CwxIniParse cnf;
  if (!cnf.load(conf_file)) {
    strcpy(err_msg_, cnf.getErrMsg());
    return -1;
  }
	/*****************load common config**************************/
  // load common:home
  if (!cnf.getAttr("common", "home", value) || !value.length()) {
    snprintf(err_msg_, 2047, "Must set [common:home] for running path.");
    return -1;
  }
  common_.work_home_ = value;
  if ('/' != value[value.length()-1]) common_.work_home_ +="/";
  // load common:host
  if (!cnf.getAttr("common", "host", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:host].");
    return -1;
  }
  CwxHostInfo host_id;
  if (!dcmd_parse_host_port(value, host_id)){
    snprintf(err_msg_, 2047, "[common:host] must be in format for host:port");
    return -1;
  }
  common_.host_id_ = value;
  // load common:agent_listen
  if (!cnf.getAttr("common", "agent_listen", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:agent_listen]");
    return -1;
  }
  if (!dcmd_parse_host_port(value, common_.agent_listen_)){
    snprintf(err_msg_, 2047, "[common:agent_listen] must be in format for host:port");
    return -1;
  }
  // load common:ui_listen
  if (!cnf.getAttr("common", "ui_listen", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:ui_listen]");
    return -1;
  }
  if (!dcmd_parse_host_port(value, common_.ui_listen_)){
    snprintf(err_msg_, 2047, "[common:ui_listen] must be in format for host:port");
    return -1;
  }
  // load common:heatbeat
  if (cnf.getAttr("common", "heatbeat", value) && value.length()){
    common_.heatbeat_internal_ = strtoul(value.c_str(), NULL, 10);
    if (common_.heatbeat_internal_ < kMinHeatbeatSecond)
      common_.heatbeat_internal_ = kMinHeatbeatSecond;
    if (common_.heatbeat_internal_ > kMaxHeatbeatSecond)
      common_.heatbeat_internal_ = kMaxHeatbeatSecond;
  }
  // load common:opr_overflow_threshold
  if (cnf.getAttr("common", "opr_overflow_threshold", value) && value.length()){
    common_.opr_overflow_threshold_ = strtoul(value.c_str(), NULL, 10);
    if (common_.opr_overflow_threshold_ < kMinOprOverflowThreshold)
      common_.opr_overflow_threshold_ = kMinOprOverflowThreshold;
    if (common_.opr_overflow_threshold_ > KMaxOprOverflowThreshold)
      common_.opr_overflow_threshold_ = KMaxOprOverflowThreshold;
  }
  // load common:opr_script_max_output_msize
  if (cnf.getAttr("common", "max_package_msize", value) && value.length()){
    common_.agent_package_size_ = strtoul(value.c_str(), NULL, 10);
    if (common_.agent_package_size_ < kMinMaxPackageMSize)
      common_.agent_package_size_ = kMinMaxPackageMSize;
    if (common_.agent_package_size_ > kMaxMaxPackageMSize)
      common_.agent_package_size_ = kMaxMaxPackageMSize;
  }
  // load common:ip_table_refresh_internal
  if (cnf.getAttr("common", "ip_table_refresh_internal", value) || !value.length()){
    common_.ip_refresh_interanl_ = strtoul(value.c_str(), NULL, 10);
    if (common_.ip_refresh_interanl_ < kMinIpTableRefreshSecond)
      common_.ip_refresh_interanl_ = kMinIpTableRefreshSecond;
    if (common_.ip_refresh_interanl_ > kMaxIpTableRefreshSecond)
      common_.ip_refresh_interanl_ = kMaxIpTableRefreshSecond;
  }
  // load common:ui_user
  if (!cnf.getAttr("common", "ui_user", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:ui_user].");
    return -1;
  }
  common_.ui_user_name_ = value;
  // load common:ui_passwd
  if (!cnf.getAttr("common", "ui_passwd", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:ui_passwd].");
    return -1;
  }
  common_.ui_user_passwd_ = value;
  // load common:opr_script_path
  if (!cnf.getAttr("common", "opr_script_path", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:opr_script_path].");
    return -1;
  }
  common_.opr_script_path_ = value;
  if ('/' != value[value.length()-1]) common_.opr_script_path_ +="/";
  // load common:task_script_path
  if (!cnf.getAttr("common", "task_script_path", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:task_script_path].");
    return -1;
  }
  common_.task_script_path_ = value;
  if ('/' != value[value.length()-1]) common_.task_script_path_ +="/";
  // load common:cron_script_path
  if (!cnf.getAttr("common", "cron_script_path", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:cron_script_path].");
    return -1;
  }
  common_.cron_script_path_ = value;
  if ('/' != value[value.length()-1]) common_.cron_script_path_ +="/";
  // load common:monitor_script_path
  if (!cnf.getAttr("common", "monitor_script_path", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [common:monitor_script_path].");
    return -1;
  }
  common_.monitor_script_path_ = value;
  if ('/' != value[value.length()-1]) common_.monitor_script_path_ +="/";
  // load common:illegal_agent_block_second
  if (cnf.getAttr("common", "illegal_agent_block_second", value) && !value.length()){
    common_.illegal_agent_block_second_ = strtoul(value.c_str(), NULL, 10);
    if (common_.illegal_agent_block_second_ < kMinIllegalAgentBlockSecond )
      common_.illegal_agent_block_second_ = kMinIllegalAgentBlockSecond;
    if (common_.illegal_agent_block_second_ > kMaxIllegalAgentBlockSecond )
      common_.illegal_agent_block_second_ = kMaxIllegalAgentBlockSecond;
  }
  // load common:enable_opr_cmd
  if (cnf.getAttr("common", "enable_opr_cmd", value) && value.length()){
    common_.is_allow_opr_cmd_ = (value=="yes")?true:false;
  }
  // load common:log_file_num
  if (cnf.getAttr("common", "log_file_num", value) && value.length()){
    common_.log_file_num_ = strtoul(value.c_str(), NULL, 10);
    if (common_.log_file_num_ < kMinLogFileNum )
      common_.log_file_num_ = kMinLogFileNum;
    if (common_.log_file_num_ > kMaxLogFileNum )
      common_.log_file_num_ = kMaxLogFileNum;
  }
  // load common:log_file_msize
  if (cnf.getAttr("common", "log_file_msize", value) && value.length()){
    common_.log_file_size_ = strtoul(value.c_str(), NULL, 10);
    if (common_.log_file_size_ < kMinLogFileMSize )
      common_.log_file_size_ = kMinLogFileMSize;
    if (common_.log_file_size_ > kMaxLogFileMSize )
      common_.log_file_size_ = kMaxLogFileMSize;
  }
  // load common:debug
  if (cnf.getAttr("common", "debug", value) && value.length()){
    common_.is_debug_ = (value=="yes")?true:false;
  }
  // load common:opr_cmd_arg_mutable
  if (cnf.getAttr("common", "opr_cmd_arg_mutable", value) && value.length()){
    common_.is_opr_cmd_arg_mutable_ = (value=="yes")?true:false;
  }
  // load common:opr_cmd_history
  if (cnf.getAttr("common", "opr_cmd_history", value) && value.length()){
    common_.is_opr_cmd_history_ = (value=="yes")?true:false;
  }
  // load common:allow_ui_net
  common_.allow_ui_ips_.clear();
  if (cnf.getAttr("common", "allow_ui_net", value) && value.length()){
    list<string> items;
    CwxCommon::split(value, items, ';');
    list<string>::iterator iter = items.begin();
    string ip;
    while(iter != items.end()) {
      ip = *iter;
      CwxCommon::trim(ip);
      common_.allow_ui_ips_.insert(ip);
      ++iter;
    }
  }
  // load mysql 
  // load mysql:server
  if (!cnf.getAttr("mysql", "server", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [mysql:server] for mysql server.");
    return -1;
  }
  mysql_.server_ = value;

  // load mysql:port
  if (!cnf.getAttr("mysql", "port", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [mysql:port] for mysql port.");
    return -1;
  }
  mysql_.port_ = strtoul(value.c_str(), NULL, 10);

  // load mysql:user
  if (!cnf.getAttr("mysql", "user", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [mysql:user] for mysql user.");
    return -1;
  }
  mysql_.user_ = value;
  // load mysql:passwd
  if (!cnf.getAttr("mysql", "passwd", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [mysql:passwd] for mysql passwd.");
    return -1;
  }
  mysql_.passwd_ = value;
  // load mysql:db_name
  if (!cnf.getAttr("mysql", "db_name", value) || !value.length()){
    snprintf(err_msg_, 2047, "Must set [mysql:db_name] for mysql database name.");
    return -1;
  }
  mysql_.db_name_ = value;
  return 0;
}
void DcmdCenterConf::OutputConfig() const {
  CWX_INFO(("*****************BEGIN CONF*******************"));
  CWX_INFO(("*****************common conf*******************"));
  CWX_INFO(("home=%s", common_.work_home_.c_str()));
  CWX_INFO(("host=%s", common_.host_id_.c_str()));
  CWX_INFO(("agent_listen=%s:%u", common_.agent_listen_.getHostName().c_str(),
    common_.agent_listen_.getPort()));
  CWX_INFO(("ui_listen=%s:%u", common_.ui_listen_.getHostName().c_str(),
    common_.ui_listen_.getPort()));
  CWX_INFO(("heatbeat=%u", common_.heatbeat_internal_));
  CWX_INFO(("opr_script_max_output_msize=%u", common_.agent_package_size_));
  CWX_INFO(("node_ip_reload_second=%u", common_.ip_refresh_interanl_));
  CWX_INFO(("ui_user=%s", common_.ui_user_name_.c_str()));
  CWX_INFO(("ui_passwd=%s", common_.ui_user_passwd_.c_str()));
  CWX_INFO(("opr_script_path=%s", common_.opr_script_path_.c_str()));
  CWX_INFO(("task_script_path=%s", common_.task_script_path_.c_str()));
  CWX_INFO(("cron_script_path=%s", common_.cron_script_path_.c_str()));
  CWX_INFO(("monitor_script_path=%s", common_.monitor_script_path_.c_str()));
  CWX_INFO(("illegal_agent_block_second=%u", common_.illegal_agent_block_second_));
  CWX_INFO(("enable_opr_cmd=%s", common_.is_allow_opr_cmd_?"yes":"no"));
  CWX_INFO(("log_file_num=%u", common_.log_file_num_));
  CWX_INFO(("log_file_msize=%u", common_.log_file_size_));
  CWX_INFO(("debug=%s", common_.is_debug_?"yes":"no"));
  CWX_INFO(("opr_cmd_arg_mutable=%s", common_.is_opr_cmd_arg_mutable_?"yes":"no"));
  CWX_INFO(("opr_cmd_history=%s", common_.is_opr_cmd_history_?"yes":"no"));
  string value;
  set<string>::const_iterator iter =  common_.allow_ui_ips_.begin();
  while (iter != common_.allow_ui_ips_.end()) {
    if (value.length()) value += ";";
    value += *iter;
    ++iter;
  }
  CWX_INFO(("allow_ui_net=%s", value.length()?value.c_str():""));
  CWX_INFO(("*****************mysql conf*******************"));
  CWX_INFO(("server=%s", mysql_.server_.c_str()));
  CWX_INFO(("port=%u", mysql_.port_));
  CWX_INFO(("usr=%s", mysql_.user_.c_str()));
  CWX_INFO(("passwd=%s", mysql_.passwd_.c_str()));
  CWX_INFO(("db=%s", mysql_.db_name_.c_str()));
  CWX_INFO(("*****************END CONF*******************"));
}
}

