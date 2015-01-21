#ifndef __DCMD_DEF_H__
#define __DCMD_DEF_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxCommon.h>
#include <CwxHostInfo.h>

#include "dcmd_macro.h"

namespace dcmd {
// 解析host:port的格式
bool dcmd_parse_host_port(string const& host_port, CwxHostInfo& host);
// escape mysql的特殊字符
void dcmd_escape_mysql_string(string& value);
// 获取内容的md5
void dcmd_md5(char const* content, uint32_t len, string& md5);
// 消除span的字符
void dcmd_remove_spam(string& value);
}  // dcmd

#endif

