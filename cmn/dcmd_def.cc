#include "dcmd_def.h"

#include <CwxMd5.h>
#include <CwxFile.h>

namespace dcmd {
bool dcmd_parse_host_port(string const& host_port, CwxHostInfo& host) {
  if ((host_port.find(':') == string::npos) || (0 == host_port.find(':')))
    return false;
  host.setHostName(host_port.substr(0, host_port.find(':')));
  host.setPort(atoi(host_port.substr(host_port.find(':') + 1).c_str()));
  return true;
}

void dcmd_escape_mysql_string( string& value){
    CwxCommon::replaceAll(value, "\\", "\\\\");
    CwxCommon::replaceAll(value, "'", "\\'");
    CwxCommon::replaceAll(value, "\"", "\\\"");
}

void dcmd_md5(char const* content, uint32_t len, string& md5) {
  CwxMd5 md5_obj;
  unsigned char md5_sign[16];
  char md5_str[33];
  md5_obj.update((unsigned char const*)content, len);
  md5_obj.final(md5_sign);
  for (uint32_t i=0; i<16; i++){
    sprintf(md5_str + i *2, "%2.2x", md5_sign[i]);
  }
  md5 = md5_str;
}

void dcmd_remove_spam(string& value) {
  CwxCommon::replaceAll(value, "\n", "");
  CwxCommon::replaceAll(value, "\r", "");
  CwxCommon::replaceAll(value, " ", "");
  CwxCommon::replaceAll(value, "|", "");
  CwxCommon::replaceAll(value, "\"", "");
  CwxCommon::replaceAll(value, "'", "");
  CwxCommon::replaceAll(value, ";", "");
  CwxCommon::replaceAll(value, "&", "");
}
}

