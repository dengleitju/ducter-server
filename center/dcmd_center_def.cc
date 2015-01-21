#include "dcmd_center_def.h"
namespace dcmd {
bool DcmdCenterSvrPool::AddSubtask(DcmdCenterSubtask* subtask) {
  if (all_subtasks_.find(subtask->subtask_id_) != all_subtasks_.end())
    return false;
  all_subtasks_[subtask->subtask_id_] = subtask;
  switch(subtask->state_) {
  case dcmd_api::SUBTASK_DOING:
    if (subtask->is_ignored_) {
      ignored_doing_subtasks_[subtask->subtask_id_] = subtask;
    } else {
      doing_subtasks_[subtask->subtask_id_] = subtask;
    }
    break;
  case dcmd_api::SUBTASK_FINISHED:
    finished_subtasks_[subtask->subtask_id_] = subtask;
    break;
  case dcmd_api::SUBTASK_FAILED:
    if (subtask->is_ignored_) {
      ignored_failed_subtasks_[subtask->subtask_id_] = subtask;
    } else {
      failed_subtasks_[subtask->subtask_id_] = subtask;
    }
    break;
  case dcmd_api::SUBTASK_INIT:
    undo_subtasks_[subtask->subtask_id_] = subtask;
    break;
  default:
    CWX_ASSERT(0);
  }
  return true;
}
// 从池子中删除subtask
bool DcmdCenterSvrPool::RemoveSubtask(DcmdCenterSubtask* subtask) {
  if (all_subtasks_.find(subtask->subtask_id_) == all_subtasks_.end())
    return false;
  all_subtasks_.erase(subtask->subtask_id_);
  switch(subtask->state_) {
  case dcmd_api::SUBTASK_DOING:
    if (subtask->is_ignored_) {
      ignored_doing_subtasks_.erase(subtask->subtask_id_);
    } else {
      doing_subtasks_.erase(subtask->subtask_id_);
    }
    break;
  case dcmd_api::SUBTASK_FINISHED:
    finished_subtasks_.erase(subtask->subtask_id_);
    break;
  case dcmd_api::SUBTASK_FAILED:
    if (subtask->is_ignored_) {
      ignored_failed_subtasks_.erase(subtask->subtask_id_);
    } else {
      failed_subtasks_.erase(subtask->subtask_id_);
    }
    break;
  case dcmd_api::SUBTASK_INIT:
    undo_subtasks_.erase(subtask->subtask_id_);
    break;
  default:
    CWX_ASSERT(0);
  }
  return true;
}

bool DcmdCenterSvrPool::ChangeSubtaskState(uint64_t subtask_id,
  uint8_t state, bool is_ignored)
{
  // 首先删除subtask
  map<uint64_t, DcmdCenterSubtask*>::iterator iter = all_subtasks_.find(subtask_id);
  if (iter == all_subtasks_.end()) return false;
  DcmdCenterSubtask* subtask = iter->second;
  if ((subtask->state_ == state) && (subtask->is_ignored_ == is_ignored)) return true;
  all_subtasks_.erase(iter);
  switch(subtask->state_) {
  case dcmd_api::SUBTASK_DOING:
    if (subtask->is_ignored_) {
      iter = ignored_doing_subtasks_.find(subtask_id);
      CWX_ASSERT(iter != ignored_doing_subtasks_.end());
      ignored_doing_subtasks_.erase(iter);
    } else {
      iter = doing_subtasks_.find(subtask_id);
      CWX_ASSERT(iter != doing_subtasks_.end());
      doing_subtasks_.erase(iter);
    }
    break;
  case dcmd_api::SUBTASK_FINISHED:
    iter = finished_subtasks_.find(subtask_id);
    CWX_ASSERT(iter != finished_subtasks_.end());
    finished_subtasks_.erase(iter);
    break;
  case dcmd_api::SUBTASK_FAILED:
    if (subtask->is_ignored_) {
      iter = ignored_failed_subtasks_.find(subtask_id);
      CWX_ASSERT(iter != ignored_failed_subtasks_.end());
      ignored_failed_subtasks_.erase(iter);
    } else {
      iter = failed_subtasks_.find(subtask_id);
      CWX_ASSERT(iter != failed_subtasks_.end());
      failed_subtasks_.erase(iter);
    }
    break;
  case dcmd_api::SUBTASK_INIT:
    iter = undo_subtasks_.find(subtask_id);
    CWX_ASSERT(iter != undo_subtasks_.end());
    undo_subtasks_.erase(iter);
    break;
  default:
    CWX_ASSERT(0);
  }
  subtask->is_ignored_ = is_ignored;
  subtask->state_ = state;
  return AddSubtask(subtask);
}
bool DcmdCenterSvrPool::IsSubtaskStatsChanged() const {
  if (undo_subtask_num_ != undo_subtasks_.size()) return true;
  if (doing_subtask_num_ != doing_subtasks_.size()) return true;
  if (failed_subtask_num_ != failed_subtasks_.size()) return true;
  if (finished_subtask_num_ != finished_subtasks_.size()) return true;
  if (ignored_doing_subtask_num_ != ignored_doing_subtasks_.size()) return true;
  if (ignored_failed_subtask_num_ != ignored_failed_subtasks_.size()) return true;
  return false;
}
void DcmdCenterSvrPool::UpdateSubtaskStats() {
  undo_subtask_num_ = undo_subtasks_.size();
  doing_subtask_num_ = doing_subtasks_.size();
  failed_subtask_num_ = failed_subtasks_.size();
  finished_subtask_num_ = finished_subtasks_.size();
  ignored_doing_subtask_num_ = ignored_doing_subtasks_.size();
  ignored_failed_subtask_num_ = ignored_failed_subtasks_.size();
}

uint8_t DcmdCenterSvrPool::GetState(uint32_t doing_rate) const {
  if (EnableSchedule(doing_rate)) return dcmd_api::TASK_DOING;
  // 已经不能调度了.
  // 若还有没有完成的任务，则处于正在做的状态
  if (doing_subtasks_.size()) return dcmd_api::TASK_DOING;
  // 若达到了失败的上限，则失败
  if (IsReachFailedThreshold(doing_rate)) return dcmd_api::TASK_FAILED;
  CWX_ASSERT(!undo_subtasks_.size());
  if (failed_subtasks_.size() || ignored_failed_subtasks_.size() || ignored_doing_subtasks_.size())
    return dcmd_api::TASK_FINISHED_WITH_FAILED;
  return dcmd_api::TASK_FINISHED;
}

bool DcmdCenterTask::AddSvrPool(DcmdCenterSvrPool* pool) {
  if (pools_.find(pool->svr_pool_) != pools_.end()) return false;
  pools_[pool->svr_pool_] = pool;
  return true;
}

DcmdCenterSvrPool* DcmdCenterTask::GetSvrPool(string const& pool_name) {
  map<string, DcmdCenterSvrPool*>::iterator iter = pools_.find(pool_name);
  if ( iter == pools_.end()) return NULL;
  return iter->second;
}

uint32_t DcmdCenterTask::GetSvrPoolId(string const& pool_name) {
  map<string, DcmdCenterSvrPool*>::iterator iter = pools_.find(pool_name);
  if ( iter == pools_.end()) return 0;
  return iter->second->svr_pool_id_;
}

bool DcmdCenterTask::AddSubtask(DcmdCenterSubtask* subtask) {
  CWX_ASSERT(subtask->svr_pool_);
  CWX_ASSERT(subtask->task_ == this);
  return subtask->svr_pool_->AddSubtask(subtask);
}

// 从池子中删除subtask
bool DcmdCenterTask::RemoveSubtask(DcmdCenterSubtask* subtask) {
  CWX_ASSERT(subtask->svr_pool_);
  CWX_ASSERT(subtask->task_ == this);
  return subtask->svr_pool_->RemoveSubtask(subtask);
}

bool DcmdCenterTask::ChangeSubtaskState(DcmdCenterSubtask const* subtask,
  uint8_t state,
  bool is_ignored)
{
  CWX_ASSERT(subtask->svr_pool_);
  return subtask->svr_pool_->ChangeSubtaskState(subtask->subtask_id_, state, is_ignored);
}
}  // dcmd

