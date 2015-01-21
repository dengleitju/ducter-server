#ifndef __DCMD_PROCESS_H__
#define __DCMD_PROCESS_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <sys/types.h>
#include <sys/wait.h>
#include <list>
#include <pwd.h>
#include <stdint.h>
#include <string>
#include <unistd.h>
#include "dcmd_macro.h"
namespace dcmd {
class DcmdProcess {
 public:
  DcmdProcess(string const& exec_file) : exec_file_(exec_file){
    pid_ = -1;
    start_time_ = 0;
    status_ = 0;
    process_args_ = NULL;
    process_envs_ = NULL;
  }
  virtual ~DcmdProcess() {
    if (process_args_) delete [] process_args_;
    if (process_envs_) delete [] process_envs_;
    if (IsRuning()) Kill(true);
  }

 public:
  // 执行定义的进程.
  bool Run(char const* user = NULL, 
    list<string> const* process_arg = NULL,
    list<string> const* process_env = NULL,
    string* err_msg = NULL);
  // kill掉进程，若is_kill_child=true，则一并kill掉所有的相关child
  void Kill(bool is_kill_child);
  // 阻塞方式wait进程退出。对于正常、异常退出，都可以通过status()获取进程退出值
  //返回值：-1：wait失败；1进程正常退出；2：进程异常退出
  int Wait(string& err_msg);
  //  非阻塞方式wait进程退出。对于正常、异常退出，都可以通过status()获取进程退出值
  // 返回值：-1：wait失败；0：进程还在运行；1：进程正常退出；2：进程异常退出
  int TryWait(string& err_msg);
  // 进程是否运行
  bool IsRuning() const;
  // 进程的exit()代码
  inline int return_code() const {  return WEXITSTATUS(status_);}
  inline uint32_t start_time() const { return start_time_; }
  inline pid_t pid() const { return pid_; }
  inline int status() const { return status_; }
  inline string const& exec_file() const { return exec_file_; }

 private:
  // fork后执行的程序
  string          exec_file_;
  // 进程的pid
  pid_t           pid_;
  // 进程启动的时间
  uint32_t        start_time_;
  // 进程退出的状态码
  int             status_;
  // 进程运行的参数指针
  char**          process_args_;
  // 进程运行的环境指针
  char**          process_envs_;
};
}  // dcmd
#endif // DCMD_SERVER_PROCESS_H_

