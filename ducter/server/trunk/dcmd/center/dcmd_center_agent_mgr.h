#ifndef __DCMD_CENTER_AGENT_MGR_H__
#define __DCMD_CENTER_AGENT_MGR_H__
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
#include "dcmd_mysql.h"

namespace dcmd {
  class DcmdCenterApp;
  // 连接对象
  class DcmdAgentConnect{
  public:
    DcmdAgentConnect() {
      create_time_ = time(NULL);
      last_heatbeat_time_ = time(NULL);
      is_master_report_reply_ = false;
      conn_id_ = 0;
    }
    DcmdAgentConnect(string const& conn_ip, uint32_t conn_id){
      create_time_ = time(NULL);
      last_heatbeat_time_ = time(NULL);
      is_master_report_reply_= false;
      conn_id_ = conn_id;
      conn_ip_ = conn_ip;
    }
    DcmdAgentConnect& operator=(DcmdAgentConnect const& item){
      if (this == &item) return *this;
      create_time_ = item.create_time_;
      last_heatbeat_time_ = item.last_heatbeat_time_;
      is_master_report_reply_ = item.is_master_report_reply_;
      conn_id_ = item.conn_id_;
      agent_ip_ = item.agent_ip_;
      conn_ip_ = item.conn_ip_;
      report_agent_ips_ = item.report_agent_ips_;
      version_ = item.version_;
      hostname_ = item.hostname_;
      return *this;
    }
    DcmdAgentConnect(DcmdAgentConnect const& item){
      create_time_ = item.create_time_;
      last_heatbeat_time_ = item.last_heatbeat_time_;
      is_master_report_reply_ = item.is_master_report_reply_;
      conn_id_ = item.conn_id_;
      agent_ip_ = item.agent_ip_;
      conn_ip_ = item.conn_ip_;
      report_agent_ips_ = item.report_agent_ips_;
      version_ = item.version_;
      hostname_ = item.hostname_;
    }
  public:
    // 连接建立的时间
    uint32_t           create_time_;
    // 上次心跳时间
    uint32_t            last_heatbeat_time_;
    // 是否已经report
    bool                is_master_report_reply_;
    // agent的连接id
    uint32_t            conn_id_;
    // agent的ip地址
    string              agent_ip_;
    // 连接的ip地址
    string              conn_ip_;
    // agent报告的所有ip地址
    string              report_agent_ips_;
    // agent的版本
    string              version_;
    // 主机名
    string              hostname_;
  };

  // 非法的agent连接对象
  class DcmdIllegalAgentConnect{
  public:
    DcmdIllegalAgentConnect(){
      conn_time_ = time(NULL);
    }
    ///构造函数
    DcmdIllegalAgentConnect(string const& conn_ip, string const& report_ips, string const& hostname, string const& version){
      conn_time_ = time(NULL);
      conn_ip_ = conn_ip;
      report_agent_ips_ = report_ips;
      hostname_ = hostname;
      version_ = version;
    }
    DcmdIllegalAgentConnect& operator=(DcmdIllegalAgentConnect const& item){
      if (this == &item) return *this;
      conn_time_ = item.conn_time_;
      conn_ip_ = item.conn_ip_;
      report_agent_ips_ = item.report_agent_ips_;
      hostname_ = item.hostname_;
      version_ = item.version_;
      return *this;
    }
    DcmdIllegalAgentConnect(DcmdIllegalAgentConnect const& item){
      conn_time_ = item.conn_time_;
      conn_ip_ = item.conn_ip_;
      report_agent_ips_ = item.report_agent_ips_;
      hostname_ = item.hostname_;
      version_ = item.version_;
    }
  public:
    // 连接时间戳。
    uint32_t            conn_time_;
    // 连接的ip
    string              conn_ip_;
    // 报告的agnet ip地址
    string              report_agent_ips_;
    // 主机名
    string              hostname_;
    // 版本
    string              version_;
  };

  // agent管理对象
  class DcmdCenterAgentMgr{
  public:
    DcmdCenterAgentMgr(DcmdCenterApp* app){
      app_ = app;
      ip_table_ = NULL;
      ip_table_last_load_time_ = 0;
    }
    ~DcmdCenterAgentMgr(){
      {
        map<uint32_t, DcmdAgentConnect*>::iterator iter = conn_agents_.begin();
        while (iter != conn_agents_.end()) {
          delete iter->second;
          ++iter;
        }
      }
      if (ip_table_) delete ip_table_;
      {
        list<DcmdIllegalAgentConnect*>::iterator iter = illegal_agent_list_.begin();
        while (iter != illegal_agent_list_.end()) {
          delete *iter;
          ++iter;
        }
      }
    }
  public:
    // 添加新连接；false表示连接存在
    bool AddConn(uint32_t conn_id,// 连接id
      char const* conn_ip // 连接的ip
      );
    // 删除连接；false表示连接不存在
    bool RemoveConn(uint32_t conn_id);
    // 设置某个ip被鉴权，0：成功；1：agent_ip存在；2：conn_id不存在
    int Auth(uint32_t conn_id, // 连接的id
      string const& agent_ip, // agent报告的agent ip
      string const& version, // agent的版本信息
      string const & report_ips, // 报告的agent的所有ip
      string const & hostname, // 报告的主机名
      string& old_conn_ip, // 若agent ip存在，则返回存在agent ip对应的连接ip
      uint32_t& old_conn_id, // 若agent ip存在，则返回存在agent ip对应的连接id
      string& old_hostname // 若agent ip存在，则返回存在agent的主机名
      );
    // 对取消对某个ip的auth动作
    void UnAuth(string const& agent_ip);
    // agent回复center的master通知。0：成功；1：没有报告自己的信息；2：conn_id不存在
    int MasterNoticeReply(uint32_t conn_id);
    // 指定agent ip是否存在
    bool IsExistAgentIp(string const& agent_ip);
    // 是否回复了master的通知
    bool IsMasterNoticeReply(string const& agent_ip);
    // 清空master的通知的回复
    void ClearMasterNoticeReportReply();
    // Agent的心跳
    void Heatbeat(uint32_t conn_id, string const& host_name);
    // 往agent_ip指定的主机发送消息；false表示发送失败
    bool SendMsg(string const& agent_ip, CwxMsgBlock* msg, uint32_t& conn_id);
    // 往conn_id指定的主机发送消息；false表示发送失败
    bool SendMsg(uint32_t conn_id, CwxMsgBlock* msg);
    // 往所有的主机发送信息
    void BroadcastMsg(CwxMsgBlock* msg);
    // 检查失去心跳的agent
    void CheckHeatbeat();
    // 刷新agent
    void RefreshAgent();
    // 获取agent的状态信息
    void GetAgentStatus(list<string> const& agent_ips, bool fetch_version,
      dcmd_api::UiAgentInfoReply& result);
    // 获取连接ip，false表示不存在
    bool GetConnIp(uint32_t conn_id, string& conn_ip);
    // 获取agent的ip，false表示不存在
    bool GetAgentIp(uint32_t conn_id, string& agent_ip);
    // 关闭指定agent的链接
    void CloseAgent(string const& agent_ip);
    // 根据agent报告的ip列表确定agent的agent ip
    bool ComfirmAgentIpByReportedIp(list<string> const& report_ips,
      string& agent_ip);
    // 添加新的无效连接；false表示连接存在
    bool AddInvalidConn(string const& conn_ip, // 连接ip
      string const&  report_ips, // 报告的ip
      string const& hostname, // 主机名
      string const& version // 版本
      );
    // 指定连接ip是否为无效的连接ip地址
    bool IsInvalidConnIp(string const& conn_ip);
    // 获取非法的agent列表
    void GetInvalidAgent(dcmd_api::UiInvalidAgentInfoReply& result);
    // 获取Agent主机名，返回0：不存在；1：认证的agent；2：未认证的agent
    int GetAgentHostName(string const& agent_ip, string & hostname);
    // 认证illegal的主机，返回true：成功；false：主机不存在
    bool AuthIllegalAgent(string const& conn_ip);
    ///刷新ip/state
    void RefreshIpState(uint32_t now);

  private:
    // Load node数据
    void LoadNode();
  private:
    // app对象
    DcmdCenterApp*                        app_;
    // 访问互斥锁
    CwxMutexLock                          lock_;
    // 基于连接id的连接map
    map<uint32_t, DcmdAgentConnect*>      conn_agents_;
    // 基于agent ip的连接map，只有被鉴权的才会进入此map。
    map<string, DcmdAgentConnect*>        ip_agents_;
    // 存放ip的表
    set<string>*                          ip_table_;
    // 上一次加载时间
    uint32_t                              ip_table_last_load_time_;
    // 基于连接时间的list
    list<DcmdIllegalAgentConnect*>        illegal_agent_list_;
    // 基于连接ip的map。
    map<string, DcmdIllegalAgentConnect*> illegal_agent_map_;
    // 服务器状态map互斥锁
    CwxMutexLock                          lock_state_;
  };
}  // dcmd
#endif

