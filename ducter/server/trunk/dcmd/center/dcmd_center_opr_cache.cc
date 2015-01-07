#include "dcmd_center_opr_cache.h"

namespace dcmd {
void DcmdCenterOprCache::AddOprCmd(string const& dup_opr_name, DcmdCenterOprCmd const& cmd) {
  CwxMutexGuard<CwxMutexLock>  lock(&lock_);
  map<string, DcmdCenterOprCmd*>::iterator iter = cmd_cache_.find(dup_opr_name);
  if(iter != cmd_cache_.end()){
    *iter->second = cmd;
    iter->second->opr_name_ = dup_opr_name;
  }else{
    DcmdCenterOprCmd* pcmd = new DcmdCenterOprCmd(cmd);
    pcmd->opr_name_ = dup_opr_name;
    cmd_cache_[dup_opr_name] = pcmd;
  }
}
bool DcmdCenterOprCache::GetOprCmd(string const& dup_opr_name, DcmdCenterOprCmd& cmd) {
  CwxMutexGuard<CwxMutexLock>  lock(&lock_);
  uint32_t now = time(NULL);
  map<string, DcmdCenterOprCmd*>::iterator iter = cmd_cache_.find(dup_opr_name);
  if(iter != cmd_cache_.end()){
    if (iter->second->expire_time_ < now) {
      cmd_cache_.erase(iter);
      return false;
    }
    cmd = *iter->second;
    return true;
  }
  return false;
}
void DcmdCenterOprCache::CheckTimeout(uint32_t now){
  CwxMutexGuard<CwxMutexLock>  lock(&lock_);
  list<string> erase_item;
  map<string, DcmdCenterOprCmd*>::iterator iter = cmd_cache_.begin();
  while(iter != cmd_cache_.end()){
    if (iter->second->expire_time_ <= now){
      erase_item.push_back(iter->first);
    }
    ++iter;
  }
  list<string>::iterator erase_iter = erase_item.begin();
  while (erase_iter != erase_item.end()) {
    cmd_cache_.erase(*erase_iter);
    erase_iter++;
  }
  
}
}  // dcmd

