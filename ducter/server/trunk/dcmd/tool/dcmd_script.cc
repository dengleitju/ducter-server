/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include "CwxGetOpt.h"
#include "CwxSockStream.h"
#include "CwxINetAddr.h"
#include "CwxSockConnector.h"
#include "CwxMsgBlock.h"
#include "CwxMsgHead.h"
#include "dcmd_tss.h"
#include "dcmd_cmn.pb.h"
#include "dcmd_ui.pb.h"

using namespace cwinux;
string     g_host;
uint16_t   g_port = 0;
int        g_client_id = 0;
string     g_script;
uint8_t    g_script_type=dcmd_api::SCRIPT_TYPE_TASK_CMD;
string     g_user;
string     g_passwd;
///-1：失败；0：help；1：成功
int parse_arg(int argc, char**argv) {
  CwxGetOpt cmd_option(argc, argv, "H:P:c:s:t:u:p:h");
  int option;
  while( (option = cmd_option.next()) != -1) {
    switch (option) {
    case 'h':
      printf("Get script infomation by script type.\n");
      printf("%s  -H host -P port -c client-id -T task-cmd  .....\n", argv[0]);
      printf("-H: server host\n");
      printf("-P: server port\n");
      printf("-c: client id\n");
      printf("-s: script name\n");
      printf("-t: script type,1:task-cmd;2:opr_cmd;3:cron_cmd;4:monitor_cmd\n");
      printf("-u: user name.\n");
      printf("-p: user password.\n");
      printf("-h: help\n");
      return 0;
    case 'H':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-H requires an argument.\n");
        return -1;
      }
      g_host = cmd_option.opt_arg();
      break;
    case 'P':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-P requires an argument.\n");
        return -1;
      }
      g_port = strtoul(cmd_option.opt_arg(), NULL, 10);
      break;
    case 'c':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-c requires an argument.\n");
        return -1;
      }
      g_client_id = strtoul(cmd_option.opt_arg(), NULL, 10);
      break;
    case 's':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-s requires an argument.\n");
        return -1;
      }
      g_script = cmd_option.opt_arg();
      break;
    case 't':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-t requires an argument.\n");
        return -1;
      }
      g_script_type = strtoul(cmd_option.opt_arg(), NULL, 10);
      break;
    case 'u':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-u requires an argument.\n");
        return -1;
      }
      g_user = cmd_option.opt_arg();
      break;
    case 'p':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-p requires an argument.\n");
        return -1;
      }
      g_passwd = cmd_option.opt_arg();
      break;
    case ':':
      printf("%c requires an argument.\n", cmd_option.opt_opt ());
      return -1;
    case '?':
      break;
    default:
      printf("Invalid arg %s.\n", argv[cmd_option.opt_ind()-1]);
      return -1;
    }
  }
  if (-1 == option) {
    if (cmd_option.opt_ind()  < argc) {
      printf("Invalid arg %s.\n", argv[cmd_option.opt_ind()]);
      return -1;
    }
  }
  if (!g_host.length()) {
    printf("No host, set by -H\n");
    return -1;
  }
  if (!g_port) {
    printf("No port, set by -P\n");
    return -1;
  }
  if (!g_script.length()){
    printf("No script name, set by -s\n");
    return -1;
  }
  if ((dcmd_api::SCRIPT_TYPE_TASK_CMD != g_script_type) &&
    (dcmd_api::SCRIPT_TYPE_OPR_CMD != g_script_type) &&
    (dcmd_api::SCRIPT_TYPE_CRON != g_script_type) &&
    (dcmd_api::SCRIPT_TYPE_MONITOR != g_script_type))
  {
    printf("Invalid script type, set by -t\n");
    return -1;
  }
  return 1;
}

int main(int argc ,char** argv) {
  int ret = parse_arg(argc, argv);
  if (0 == ret) return 0;
  if (-1 == ret) return 1;
  CwxSockStream  stream;
  CwxINetAddr  addr(g_port, g_host.c_str());
  CwxSockConnector conn;
  if (0 != conn.connect(stream, addr)) {
    printf("Failure to connect ip:port: %s:%u, errno=%d\n",
      g_host.c_str(), g_port, errno);
    return 1;
  }
  CwxMsgBlock* block=NULL;
  string query_msg;
  dcmd_api::UiScriptInfo query;

  query.set_client_msg_id(g_client_id);
  query.set_script_file(g_script);
  query.set_script_type((dcmd_api::UiScriptType)g_script_type);
  query.set_user(g_user);
  query.set_passwd(g_passwd);
  if (!query.SerializeToString(&query_msg)) {
    printf("Failure to serialize query-msg.\n");
    return 1;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_UI_SCRIPT_INFO, 0, query_msg.length());
  block = CwxMsgBlockAlloc::pack(head, query_msg.c_str(), query_msg.length());
  if (!block) {
    printf("Failure to pack query-msg.\n");
    return 1;
  }
  if (block->length() != (CWX_UINT32)CwxSocket::write_n(stream.getHandle(),
      block->rd_ptr(), block->length()))
  {
    CwxMsgBlockAlloc::free(block);
    printf("failed to send message, errno=%d\n", errno);
    return 1;
  }
  CwxMsgBlockAlloc::free(block);
  //recv msg
  if (0 >= CwxSocket::read(stream.getHandle(), head, block)) {
    printf("failed to read the reply, errno=%d\n", errno);
    return 1;
  }
  if (dcmd_api::MTYPE_UI_SCRIPT_INFO_R != head.getMsgType()) {
    printf("receive a unknow msg type, msg_type=%u\n", head.getMsgType());
    if (block) CwxMsgBlockAlloc::free(block);
    return 1;
  }
  query_msg.assign(block->rd_ptr(), block->length());
  dcmd_api::UiScriptInfoReply reply;
  if (!reply.ParseFromString(query_msg)) {
    printf("failed to parse reply-msg\n");
    if (block) CwxMsgBlockAlloc::free(block);
    return 1;
  }
  printf("script info:\n");
  printf("client_id:%d\n", reply.client_msg_id());
  printf("state:%d\n", reply.state());
  if (dcmd_api::DCMD_STATE_SUCCESS != reply.state()) {
    printf("err:%s\n", reply.err().c_str());
  } else {
    printf("md5:%s\n", reply.md5().c_str());
    printf("output:%s\n", reply.script().c_str());
  }
  return 0;
}

