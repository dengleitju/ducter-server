#ifndef __DCMD_CENTER_WATCH_MGR_H__
#define __DCMD_CENTER_WATCH_MGR_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxMutexLock.h>
#include <CwxLockGuard.h>
#include <CwxMsgBlock.h>

#include "dcmd_center_def.h"

namespace dcmd {

class DcmdCenterApp;
// Watch对象
class DcmdWatchObj{
 public:
  DcmdWatchObj() {
    ui_client_id_ = 0;
    conn_id_ = 0;
    watched_task_id_ = 0;
    is_watch_last_state_ = false;
    watch_id_ = 0;
  }

  bool operator == (DcmdWatchObj const& item) const {
    return (conn_id_ == item.conn_id_) &&
      (ui_client_id_ == item.ui_client_id_) &&
      (watched_task_id_ == item.watched_task_id_);
  }

  bool operator < (DcmdWatchObj const& item) const {
    if (conn_id_ < item.conn_id_) return true;
    if (conn_id_ > item.conn_id_) return false;
    if (ui_client_id_ < item.ui_client_id_) return true;
    if (ui_client_id_ > item.ui_client_id_) return false;
    return watched_task_id_ < item.watched_task_id_;
  }
  
 public:
  // UI的client id
  uint32_t           ui_client_id_;
  // ui的连接id
  uint32_t           conn_id_;
  // watch的task的id
  uint32_t           watched_task_id_;
  // 是否只要最终的结果
  bool               is_watch_last_state_;
  // watch的id
  uint32_t           watch_id_;
};

// watch管理对象，此对象由Task 线程处理，因此无需加锁
class DcmdCenterWatchMgr {
 typedef set<DcmdWatchObj*, CwxPointLess<DcmdWatchObj> > DcmdWatchSet;
 typedef set<DcmdWatchObj*, CwxPointLess<DcmdWatchObj> >::iterator DcmdWatchSet_Iter;
 public:
  DcmdCenterWatchMgr(DcmdCenterApp* app) {
    app_ = app;
    next_watch_id_ = 0;
  }
  ~DcmdCenterWatchMgr() {
    Clear(false);
  }
 public:
  // 添加新watch，返回watch id
  uint32_t AddWatch(uint32_t ui_client_id,
    uint32_t conn_id,
    uint32_t watch_task_id,
    bool is_watch_last_state);
  // 获取watch的id, 0表示不存在
  uint32_t GetWatchId(uint32_t ui_client_id,
    uint32_t conn_id,
    uint32_t watch_task_id) const;
  // 是否存在watch
  bool ExistWatch(uint32_t ui_client_id,
    uint32_t conn_id,
    uint32_t watch_task_id) const;

  // Cancel具体的watch
  void CancelWatch(uint32_t ui_client_id,
    uint32_t conn_id,
    uint32_t watch_task_id);

  // Cancel一条连接上的所有watch
  void CancelWatch(uint32_t conn_id);

  // 获取一条连接上的所有watch
  void GetWatchesByConn(list<DcmdWatchObj>& watches, uint32_t conn_id);

  // 获取所有的watch
  void GetWatches(list<DcmdWatchObj>& watches);

  // 通知任务信息改变
  void NoticeTaskChange(DcmdCenterTask const& task);

  // 通知master改变
  void NoticeMasterChange(bool is_close_conn);

  // 清空watch
  void Clear(bool is_close_conn);
 private:
   uint32_t watch_id() {
     next_watch_id_ ++;
     if (!next_watch_id_) next_watch_id_++;
     while (watches_.find(next_watch_id_) != watches_.end()) {
       next_watch_id_++;
       if (!next_watch_id_) next_watch_id_++;
     }
     return next_watch_id_;
   }
 private:
  // app对象
  DcmdCenterApp*                              app_;
  // 基于task id的索引, key为task id
  map<uint32_t, DcmdWatchSet* >               task_index_;
  // 基于连接的索引
  map<uint32_t, DcmdWatchSet* >               conn_index_;
  // 所有的watch对象
  map<uint32_t, DcmdWatchObj*>                watches_;
  // 当前的index
  uint32_t                                    next_watch_id_;
};
}  // dcmd
#endif

