#ifndef __DCMD_CENTER_DEF_H__
#define __DCMD_CENTER_DEF_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxCommon.h>
#include <CwxHostInfo.h>
#include "dcmd_def.h"
#include "dcmd_tss.h"

namespace dcmd {
  class DcmdCenterTask;
  class DcmdCenterSubtask;
  class DcmdCenterAgent;
  class DcmdCenterSvrPool;
  // 命令对象，为数据库command表中的记录
  class DcmdCenterCmd {
  public:
    // 命令状态定义
    enum {
      // 命令未处理
      DCMD_CMD_STATE_UNDO = 0,
      // 命令已经完成
      DCMD_CMD_STATE_FINISH = 1,
      // 命令处理失败
      DCMD_CMD_STATE_FAIL = 2
    };
  public:
    DcmdCenterCmd() {
      cmd_id_ = 0;
      task_id_ = 0;
      svr_pool_id_ = 0;
      subtask_id_ = 0;
      cmd_type_ = dcmd_api::CMD_UNKNOWN;
      state_ = DCMD_CMD_STATE_UNDO;
      begin_time_ = 0;
      task_ = NULL;
      subtask_ = NULL;
      agent_ = NULL;
    }
    ~DcmdCenterCmd() { }
  public:
    // cmd的id
    uint64_t                cmd_id_;
    // cmd对应的task id
    uint32_t                task_id_;
    // cmd对应的subtask id
    uint64_t                subtask_id_;
    // cmd对应的svr池子
    string                  svr_pool_;
    // cmd对应的svr池子的id
    uint32_t                svr_pool_id_;
    // cmd对应的service
    string                  service_;
    // cmd对应的agent的ip地址
    string                  agent_ip_;
    // 命令类型
    uint8_t                 cmd_type_;
    // 开始做的时间
    uint32_t                begin_time_;
    // 命令的状态
    uint8_t                 state_;
    // 命令对应的任务对象
    DcmdCenterTask*         task_;
    // 命令对应的subtask对象
    DcmdCenterSubtask*      subtask_;
    // 命令对应的agent对象
    DcmdCenterAgent*        agent_;
  };
  // node对象的subtask定义，对应于task_node 表
  class DcmdCenterSubtask {
  public:
    DcmdCenterSubtask() {
      subtask_id_ = 0;
      check_start_time_ = 0;
      task_id_ = 0;
      state_ = dcmd_api::SUBTASK_INIT;
      is_ignored_ = false;
      start_time_ = 0;
      finish_time_ = 0;
      exec_cmd_ = NULL;
      task_ = NULL;
      svr_pool_ = NULL;
    }
  public:
    // 子任务的id
    uint64_t                 subtask_id_;
    // 任务的id
    uint32_t                 task_id_;
    // 任务命令的名字
    string                   task_cmd_;
    // 子任务的服务池子名字
    string                   svr_pool_name_;
    // service的名字
    string                   service_;
    // subtask的ip
    string                   ip_;
    // 子任务的状态
    uint8_t                  state_;
    // 是否被ignore
    bool                     is_ignored_;
    // 子任务的开始时间
    uint32_t                 start_time_;
    // 子任务的完成时间
    uint32_t                 finish_time_;
    // 字任的超时开始检测时间
    uint32_t                 check_start_time_;
    // 子任务的进度
    string                   process_;
    // 错误信息
    string                   err_msg_;
    // 等着执行的任务
    DcmdCenterCmd*           exec_cmd_;
    // 子任务对应的task对象
    DcmdCenterTask*          task_;
    // subtask所属的svr pool
    DcmdCenterSvrPool*       svr_pool_;
  };
  // Task svr pool 对象，对应于task_service_pool表
  class DcmdCenterSvrPool {
  public:
    DcmdCenterSvrPool(uint32_t task_id): task_id_(task_id) {
      svr_pool_id_ = 0;
      undo_subtask_num_ = 0;
      doing_subtask_num_ = 0;
      failed_subtask_num_ = 0;
      finished_subtask_num_ = 0;
      ignored_doing_subtask_num_ = 0;
      ignored_failed_subtask_num_ = 0;
      state_ = dcmd_api::TASK_INIT;
    }
    ~DcmdCenterSvrPool(){ }
  public:
    // 往池子中添加subtask；true：成功；false表示存在
    bool AddSubtask(DcmdCenterSubtask* subtask);
    // 从池子中删除subtask
    bool RemoveSubtask(DcmdCenterSubtask* subtask);
    // 改变Subtask的状态；true：成功；false表示不存在
    bool ChangeSubtaskState(uint64_t subtask_id,
      uint8_t state,
      bool is_ignored);
  public:
    // 是否subtask的统计信息改变
    bool IsSubtaskStatsChanged() const;
    // 更新subtask的统计信息
    void UpdateSubtaskStats();
    // 获取任务池子的状态，其具有TASK_DOING，TASK_FAILED，TASK_FINISHED，TASK_FINISHED_WITH_FAILED四中状态
    uint8_t GetState( uint32_t doing_rate // 做的最大比率
      ) const;
    // 获取最大的并发数量
    inline uint32_t MaxContNum(uint32_t doing_rate) const {
      uint32_t max_doing_num = 0;
      if (doing_rate > 100) doing_rate = 100;
      max_doing_num = (all_subtasks_.size() * doing_rate  + 99)/100;
      return max_doing_num?max_doing_num:1;
    }
    // 是否可以调度
    inline bool EnableSchedule(uint32_t doing_rate // 做的最大比率
      ) const
    {
      uint32_t max_doing_num = MaxContNum(doing_rate);
      if (!undo_subtasks_.size()) return false;
      if (doing_host_num() + failed_host_num() >= max_doing_num) return false;
      return true;
    }
    // 是否达到失败的上限
    inline bool IsReachFailedThreshold( uint32_t doing_rate // 做的最大比率
      ) const
    {
      uint32_t max_doing_num = MaxContNum(doing_rate);
      if (failed_host_num() >= max_doing_num) return true;
      return false;
    }
    // 获取池子node的数量
    uint32_t total_host_num() const { return all_subtasks_.size(); }
    // 获取未作node的数量
    uint32_t undo_host_num() const { return undo_subtasks_.size(); }
    // 获取正在做的node的数量
    uint32_t doing_host_num() const { return doing_subtasks_.size(); }
    // 获取ignore的正在做的node的数量
    uint32_t ignored_doing_host_num() const {
      return ignored_doing_subtasks_.size();
    }
    //获取已经完成的node的数量
    uint32_t finished_host_num() const { return finished_subtasks_.size(); }
    // 获取失败的node的数量 
    uint32_t failed_host_num() const { return failed_subtasks_.size(); }
    // 获取ignore的失败node的数量
    uint32_t ignored_failed_host_num() const {
      return ignored_failed_subtasks_.size();
    }
  public:
    // 任务的id
    uint32_t                          task_id_;
    // 任务的命令
    string                            task_cmd_;
    // 服务池子的名字
    string                            svr_pool_;
    // 服务池子的id
    uint32_t                          svr_pool_id_;
    // 服务池子的环境版本
    string                            svr_env_ver_;
    // 服务池子的版本库地址
    string                            repo_;
    // 服务池子的运行用户
    string                            run_user_;
    // 下面是池子的子任务的统计信息
    // 未作的子任务
    uint32_t                          undo_subtask_num_;
    // 正在执行的子任务，不包含ignored
    uint32_t                          doing_subtask_num_;
    // 失败的子任务，不包含ignored
    uint32_t                          failed_subtask_num_;
    // 已完成的子任务
    uint32_t                          finished_subtask_num_;
    // ignored的正在做的子任务
    uint32_t                          ignored_doing_subtask_num_;
    // ignored的失败的子任务
    uint32_t                          ignored_failed_subtask_num_;
    // 池子的状态
    uint8_t                           state_;
    // 池子的属性
    map<string, string>               attr_;
    // 所有设备的subtask的map
    map<uint64_t, DcmdCenterSubtask*>      all_subtasks_;
    map<uint64_t, DcmdCenterSubtask*>      undo_subtasks_;
    map<uint64_t, DcmdCenterSubtask*>      doing_subtasks_;
    map<uint64_t, DcmdCenterSubtask*>      finished_subtasks_;
    map<uint64_t, DcmdCenterSubtask*>      failed_subtasks_;
    map<uint64_t, DcmdCenterSubtask*>      ignored_failed_subtasks_;
    map<uint64_t, DcmdCenterSubtask*>      ignored_doing_subtasks_;
  };
  // 任务对象，对应数据库的task表
  class DcmdCenterTask {
  public:
    DcmdCenterTask() {
      task_id_ = 0;
      depend_task_id_ = 0;
      depend_task_ = NULL;
      app_id_ = 0;
      svr_id_ = 0;
      is_node_multi_pool_ = false;
      update_env_ = false;
      update_tag_ = false;
      state_ = dcmd_api::TASK_INIT;
      is_freezed_ = false;
      is_valid_ = true;
      is_pause_ = false;
      max_current_rate_ = 0;
      timeout_ = 0;
      is_auto_ = false;
      is_output_process_ = false;
    }
    ~DcmdCenterTask(){
      {
        // 释放池子对象
        map<string, DcmdCenterSvrPool*>::iterator iter= pools_.begin();
        while(iter != pools_.end()){
          delete iter->second;
          ++iter;
        }
      }
    }

  public:
    // 往task中添加service池子；true 成功；false：存在
    bool AddSvrPool(DcmdCenterSvrPool* pool);
    // 获取task的池子
    DcmdCenterSvrPool* GetSvrPool(string const& pool_name);
    // 获取池子的id
    uint32_t GetSvrPoolId(string const& pool_name);
    // 添加新subtask，true 成功；false：失败。失败或者subtask存在，或者pool不存在
    bool AddSubtask(DcmdCenterSubtask* subtask);
    // 从池子中删除subtask
    bool RemoveSubtask(DcmdCenterSubtask* subtask);
    // 改变任务的状态，true 成功；false：失败。失败或者subtask不存在，或者pool不存在
    bool ChangeSubtaskState(DcmdCenterSubtask const* subtask,
      uint8_t state,
      bool is_ignored);
    // 返回值，false：不存在；true：存在
    bool GetArgValue(char const* arg_name, string& value) {
      map<string, string>::iterator iter = args_.find(string(arg_name));
      if (iter == args_.end()) return false;
      value = iter->second;
    }
    // 任务是否完成
    inline bool IsFinished() const {
      return (dcmd_api::TASK_FINISHED == state_) || (dcmd_api::TASK_FINISHED_WITH_FAILED == state_);
    }
    // 获取主机的数量
    inline uint32_t GetSubtaskNum() const {
      uint32_t num = 0;
      map<string, DcmdCenterSvrPool*>::const_iterator iter = pools_.begin();
      while(iter != pools_.end()) {
        num += iter->second->all_subtasks_.size();
        ++iter;
      }
      return num;
    }
    // 计算任务的状态
    inline uint8_t CalcTaskState() const {
      uint8_t pool_state = 0;
      map<string, DcmdCenterSvrPool*>::const_iterator iter = pools_.begin();
      bool has_pool_failed = false;
      bool has_pool_finished_with_failed = false;
      while(iter != pools_.end()) {
        pool_state = iter->second->GetState(max_current_rate_);
        if (pool_state == dcmd_api::TASK_DOING) {
          return dcmd_api::TASK_DOING;
        } else if (pool_state == dcmd_api::TASK_FAILED) {
          has_pool_failed = true;
        } else if (pool_state == dcmd_api::TASK_FINISHED_WITH_FAILED) {
          has_pool_finished_with_failed = true;
        }
        ++iter;
      }
      if (has_pool_failed) return dcmd_api::TASK_FAILED;
      if (has_pool_finished_with_failed) return dcmd_api::TASK_FINISHED_WITH_FAILED;
      return dcmd_api::TASK_FINISHED;
    }
  public:
    // 任务的id
    uint32_t                    task_id_;
    // 任务的名字
    string                      task_name_;
    // 任务的命令
    string                      task_cmd_;
    // 依赖任务的id
    uint32_t                    depend_task_id_;
    // 依赖的任务
    DcmdCenterTask*             depend_task_;
    // 被依赖的任务
    map<uint32_t,DcmdCenterTask*> depended_tasks_; 
    // 产品的id
    uint32_t                    app_id_;
    // 产品的名字
    string                      app_name_;
    // service的id
    uint32_t                    svr_id_;
    // service的名字
    string                      service_;
    // service的path
    string                      svr_path_;
    // 是否运行一个服务器上有多个服务池子
    bool                        is_node_multi_pool_;
    // tag
    string                      tag_;
    // 是否更新环境
    bool                        update_env_;
    // 是否重新取版本
    bool                        update_tag_;
    // 任务的状态
    uint8_t                     state_;
    // 任务是否被冻结
    bool                        is_freezed_;
    // 任务是否有效
    bool                        is_valid_;
    // 任务是否暂停
    bool                        is_pause_;
    // 并行执行的最大比率
    uint32_t                    max_current_rate_;
    // 执行的超时时间
    uint32_t                    timeout_;
    // 是否自动执行
    bool                        is_auto_;
    // 是否输出进度信息
    bool                        is_output_process_;
    // 参数的xml脚本
    string                      arg_xml_;
    // 任务的参数
    map<string, string>         args_;
    // 任务的脚本
    string                      task_cmd_script_;
    // 任务脚本的md5值
    string                      task_cmd_script_md5_;
    // 错误信息
    string                      err_msg_;
    // 任务的池子
    map<string, DcmdCenterSvrPool*>   pools_;
  };
  // agent节点命令对象，只有存在task命令的agent节点才存在
  class DcmdCenterAgent {
  public:
    DcmdCenterAgent(string const& ip):ip_(ip) {}
    ~DcmdCenterAgent(){  cmds_.clear(); }
  public:
    // 设备的ip地址
    string      ip_;  ///设备的ip地址
    // 节点上待发送的命令, key为cmd的id
    map<uint64_t, DcmdCenterCmd*>  cmds_;
  };
}  // dcmd
#endif

