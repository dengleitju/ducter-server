#include "dcmd_agent_config.h"
#include <CwxLogger.h>
namespace dcmd {
int DcmdAgentConfig::Init(string const& conf_file) {
  string value;
  if (!parser_.load(conf_file)){
    strcpy(err_2k, parser_.getErrMsg());
    return -1;
  }
  //load agent:home
  if (!parser_.getAttr("agent", "home", value) || !value.length()){
    snprintf(err_2k, kDcmd2kBufLen, "Must set [agent:home] for running path.");
    return -1;
  }
  dcmd_remove_spam(value);
	conf_.work_home_ = value;
	if ('/' != value[value.length()-1]) conf_.work_home_ +="/";
  //load agent:center
  if (!parser_.getAttr("agent", "center", value) || !value.length()){
    snprintf(err_2k, kDcmd2kBufLen, "Must set [agent:center]");
    return -1;
  }
  conf_.centers_.clear();
  CwxHostInfo  host;
  list<string> hosts;
  string strHost;
  bool bDup = false;
  CwxCommon::split(value, hosts, kItemSplitChar);
  list<string>::iterator iter = hosts.begin();
  while(iter != hosts.end()){
    strHost = *iter;
    CwxCommon::trim(strHost);
    if (strHost.length()){
      if (!dcmd_parse_host_port(strHost, host)){
        snprintf(err_2k, kDcmd2kBufLen, "Invalid center format:%s, it should be [host:port,host:port] format.",
          strHost.c_str());
        return -1;
      }
      bDup = false;
      list<CwxHostInfo>::iterator iter_host=conf_.centers_.begin();
      while(iter_host != conf_.centers_.end()){
        if ((iter_host->getHostName() == host.getHostName()) && 
          (iter_host->getPort() == host.getPort())) {
          bDup = true;
          break;
        }
        iter_host++;
      }
      if (!bDup) conf_.centers_.push_back(host);
    }
    iter++;
  }
  if (!conf_.centers_.size()){
    snprintf(err_2k, kDcmd2kBufLen, "Must set [agent:center] in format  [host:port;host:port]");
    return -1;
  }
  //load agent:log_file_num
  if (parser_.getAttr("agent", "log_file_num", value) && value.length()){
    conf_.log_file_num_ = strtoul(value.c_str(), NULL, 10);
    if (conf_.log_file_num_ < kMinLogFileNum ) conf_.log_file_num_ = kMinLogFileNum;
    if (conf_.log_file_num_ > kMaxLogFileNum ) conf_.log_file_num_ = kMaxLogFileNum;
  }
  //load agent:log_file_msize
  if (parser_.getAttr("agent", "log_file_msize", value) && value.length()){
    conf_.log_file_msize_ = strtoul(value.c_str(), NULL, 10);
    if (conf_.log_file_msize_ < kMinLogFileMSize) conf_.log_file_msize_ = kMinLogFileMSize;
    if (conf_.log_file_msize_ > kMaxLogFileMSize) conf_.log_file_msize_ = kMaxLogFileMSize;
  }
  //load agent:debug
  if (!parser_.getAttr("agent", "debug", value) || !value.length()){
    conf_.is_debug_ = false;
  }else{
    conf_.is_debug_ = (value=="yes")?true:false;
  }
  return 0;
}

void DcmdAgentConfig::OutputConfig() const
{
  CWX_INFO(("*****************begin agent conf*******************"));
  CWX_INFO(("home=%s", conf_.work_home_.c_str()));
  {
    list<CwxHostInfo>::const_iterator iter = conf_.centers_.begin();
    while(iter != conf_.centers_.end()){
      CWX_INFO(("center=%s:%u", iter->getHostName().c_str(), iter->getPort()));
      iter++;
    }
  }
  CWX_INFO(("log_file_num=%u", conf_.log_file_num_));
  CWX_INFO(("log_file_msize=%u", conf_.log_file_msize_));
  CWX_INFO(("debug=%s", conf_.is_debug_?"yes":"no"));
  CWX_INFO(("*****************end agent conf*******************"));
}
}

