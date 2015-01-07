#include "dcmd_center_h4_check.h"
#include "dcmd_center_app.h"
namespace dcmd {
// 检测shell的执行超时
int DcmdCenterH4Check::onTimeoutCheck(CwxMsgBlock*& , CwxTss* pThrEnv) {
  CWX_INFO(("Start check master:%s:%s",
    app_->config().common().host_id_.c_str(), app_->is_master()?"true":"false"));
  DcmdTss* tss = (DcmdTss*)pThrEnv;
  CheckMasterCenter(tss);
  CWX_INFO(("End check master:%s:%s", app_->config().common().host_id_.c_str(),
     app_->is_master()?"true":"false"));
  return 1;
}

bool DcmdCenterH4Check::GetMasterHost(Mysql* my, string& master_host, DcmdTss* tss) {
  CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
    "select host from dcmd_center where master=1 and UNIX_TIMESTAMP() < UNIX_TIMESTAMP(update_time) + %d "\
    "order by update_time desc, host asc",
    kCenterMasterSwitchTimeoutSecond);
  if (!my->query(tss->sql_)){
    my->freeResult();
    CWX_ERROR(("Failure to query master info, err:%s  sql=%s", my->getErrMsg(), tss->sql_));
    return false;
  }
  master_host.erase();
  bool bNull = false;
  while(1==my->next()){
    master_host = my->fetch(0, bNull);
    break;
  }
  my->freeResult();
  return true;
}
bool DcmdCenterH4Check::SetHeatbeat(Mysql* my, bool is_master, DcmdTss* tss) {
  CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize,
    "insert into dcmd_center(host, master, update_time) values('%s', %d, now()) \
    ON DUPLICATE KEY UPDATE master=%d, update_time=now()",
    app_->config().common().host_id_.c_str(),
    is_master?1:0,
    is_master?1:0);
  if (-1 == my->execute(tss->sql_)){
    CWX_ERROR(("Failure to update dcmd_center table,sql=%s, err=%s", tss->sql_, my->getErrMsg()));
    my->rollback();
    return false;
  }
  if (!my->commit()){
    CWX_ERROR(("Failure to commit dcmd_center table update, sql=%s, err=%s",
      tss->sql_, my->getErrMsg()));
    my->rollback();
    return false;
  }
  return true;
}
bool DcmdCenterH4Check::LockCenterTable(Mysql* my, DcmdTss* tss) {
  CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, "lock tables dcmd_center write");
  if (-1 == my->execute(tss->sql_)){
    CWX_ERROR(("Failure to lock dcmd_center table,  sql=%s, err=%s", tss->sql_, my->getErrMsg()));
    return false;
  }
  return true;
}
bool DcmdCenterH4Check::UnlockCenterTable(Mysql* my, DcmdTss* tss) {
  CwxCommon::snprintf(tss->sql_, DcmdTss::kMaxSqlBufSize, "unlock tables");
  if (-1 == my->execute(tss->sql_)) {
    CWX_ERROR(("Failure to unlock table, err=%s, sql=%s", my->getErrMsg(), tss->sql_));
    return false;
  }
  return true;
}
void DcmdCenterH4Check::CheckMasterCenter(DcmdTss* tss) {
  bool is_master = false; //是否是master
  string host;
  Mysql* my=app_->GetCheckMysql();
  do{
    // 每次数据库的连接都是重连
    if (my->IsConnected())  my->disconnect();
    // 如果连接失败，则退出
    if (!app_->ConnectMysql(my)){
      CWX_ERROR(("Failure to connect mysql for master check. err:%s", my->getErrMsg()));
      break;
    }
    // 锁表，失败则关闭连接退出
    if (!LockCenterTable(my, tss)){
      my->disconnect();
      break;
    }
    // 获取当前谁是master，若失败则退出
    if (!GetMasterHost(my, host, tss)) {
      my->disconnect();
      break;
    }
    // 检测是否我是master
    if (!host.length() || ///没有master
      (host == app_->config().common().host_id_))
    { //自己就是master
        is_master = true;
    }
    if (!SetHeatbeat(my, is_master, tss)) {
      //设置心跳，失败则退出
      my->disconnect();
      break;
    }
  }while(0);

  if (my->IsConnected()){// 如果连接存在
    if (!UnlockCenterTable(my, tss)){ // 解锁
      is_master = false;
    }
  }
  // 关闭mysql的连接
  if (my->IsConnected())  my->disconnect();
  if (is_master != app_->is_master()) {
    app_->SetMaster(is_master);
    app_->SetMasterHost(host.c_str());
    CwxMsgBlock* msg = CwxMsgBlockAlloc::malloc(0);
    msg->event().setSvrId(DcmdCenterApp::SVR_TYPE_AGENT);
    msg->event().setConnId(is_master?1:0);
    msg->event().setEvent(DcmdCenterApp::EVENT_TYPE_MASTER_CHANGE);
    // 将超时检查事件，放入事件队列
    app_->GetTaskThreadPool()->append(msg);
  }
  if (is_master) {
    CWX_INFO((" I am master, host:%s", app_->config().common().host_id_.c_str()));
  }else{
    CWX_INFO((" I am not master, master is %s", host.length()?host.c_str():""));
  }
}
}  // dcmd

