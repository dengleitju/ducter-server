#ifndef __DCMD_TSS_H__
#define __DCMD_TSS_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxLogger.h>
#include <CwxTss.h>

#include "dcmd_macro.h"
namespace dcmd {
// dcmd的tss
class DcmdTss:public CwxTss {
 public:
   // buf最大长度
   static const uint32_t kMaxSqlBufSize =  2 * 1024 * 1024;

 public:
  DcmdTss():CwxTss(){
    data_buf_ = NULL;
    data_buf_len_ = 0;
  }
  // 析构函数
  ~DcmdTss();
 
 public:
  // tss的初始化，0：成功；-1：失败
  int Init();
  // 获取package的buf，返回NULL表示失败
  inline char* GetBuf(uint32_t buf_size) {
    if (data_buf_len_ < buf_size) {
      if (data_buf_) delete [] data_buf_;
      data_buf_ = new char[buf_size];
      data_buf_len_ = buf_size;
    }
    return data_buf_;
  }
   // 读取文件内容
  bool ReadFile(char const* filename, string& file_content, string& err_msg);

 public:
  // sql的buf
  char                            sql_[kMaxSqlBufSize];
  // proto的buf
  string                          proto_str_;
  // 中间处理的错误信息
  string                          err_msg_;
 private:
  // 数据buf
  char*                           data_buf_;
  // 数据buf的空间大小
  uint32_t                        data_buf_len_;
};
}  // dcmd
#endif

