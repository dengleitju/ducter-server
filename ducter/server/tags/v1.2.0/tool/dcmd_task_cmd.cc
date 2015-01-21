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
string     g_task_id;
uint32_t   g_uid = 0;
string     g_subtask_id;
string     g_agent_ip;
string     g_svr_name;
string     g_svr_pool;
uint32_t   g_concurrent_num = 0;
uint32_t   g_concurrent_rate = 0;
uint32_t   g_timeout = 0;
bool       g_is_auto = false;
uint8_t    g_cmd_type = 0;
string     g_user;
string     g_passwd;
///-1：失败；0：help；1：成功
int parse_arg(int argc, char**argv) {
  CwxGetOpt cmd_option(argc, argv, "H:P:c:t:U:s:i:S:R:n:r:o:T:u:p:ha");
  int option;
  while( (option = cmd_option.next()) != -1) {
    switch (option) {
    case 'h':
      printf("Commit task cmd.\n");
      printf("%s  -H host -P port -c client-id -t task-id  .....\n", argv[0]);
      printf("-H: server host\n");
      printf("-P: server port\n");
      printf("-c: client id\n");
      printf("-t: task id\n");
      printf("-U: uid to commit.\n");
      printf("-s: subtask_id.\n");
      printf("-i: agent ip.\n");
      printf("-S: service name.\n");
      printf("-R: service pool name.\n");
      printf("-n: concurrent number for task's subtask.\n");
      printf("-r: concurrent rate for task's subtask.\n");
      printf("-o: task's timeout.\n");
      printf("-T: command type.\n");
      printf("-a: auto.\n");
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
    case 't':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-t requires an argument.\n");
        return -1;
      }
      g_task_id = cmd_option.opt_arg();
      break;
    case 'U':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-U requires an argument.\n");
        return -1;
      }
      g_uid = strtoul(cmd_option.opt_arg(), NULL, 0);
      break;
    case 's':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-s requires an argument.\n");
        return -1;
      }
      g_subtask_id = cmd_option.opt_arg();
      break;
    case 'i':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-i requires an argument.\n");
        return -1;
      }
      g_agent_ip = cmd_option.opt_arg();
      break;
    case 'S':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-S requires an argument.\n");
        return -1;
      }
      g_svr_name = cmd_option.opt_arg();
      break;
    case 'R':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-R requires an argument.\n");
        return -1;
      }
      g_svr_pool = cmd_option.opt_arg();
      break;
    case 'n':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-n requires an argument.\n");
        return -1;
      }
      g_concurrent_num = strtoul(cmd_option.opt_arg(), NULL, 10);
      break;
    case 'r':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-r requires an argument.\n");
        return -1;
      }
      g_concurrent_rate = strtoul(cmd_option.opt_arg(), NULL, 10);
      break;
    case 'o':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-o requires an argument.\n");
        return -1;
      }
      g_timeout = strtoul(cmd_option.opt_arg(), NULL, 10);
      break;
    case 'a':
      g_is_auto = true;
      break;
    case 'T':
      if (!cmd_option.opt_arg() || (*cmd_option.opt_arg() == '-')) {
        printf("-T requires an argument.\n");
        return -1;
      }
      g_cmd_type = strtoul(cmd_option.opt_arg(), NULL, 10);
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
  dcmd_api::UiTaskCmd query;

  query.set_client_msg_id(g_client_id);
  query.set_task_id(g_task_id);
  query.set_uid(g_uid);
  query.set_subtask_id(g_subtask_id);
  query.set_ip(g_agent_ip);
  query.set_svr_name(g_svr_name);
  query.set_svr_pool(g_svr_pool);
  query.set_concurrent_num(g_concurrent_num);
  query.set_concurrent_rate(g_concurrent_rate);
  query.set_task_timeout(g_timeout);
  query.set_auto_(g_is_auto);
  query.set_cmd_type((dcmd_api::CmdType)g_cmd_type);
  query.set_user(g_user);
  query.set_passwd(g_passwd);
  if (!query.SerializeToString(&query_msg)) {
    printf("Failure to serialize query-msg.\n");
    return 1;
  }
  CwxMsgHead head(0, 0, dcmd_api::MTYPE_UI_EXEC_TASK, 0, query_msg.length());
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
  if (dcmd_api::MTYPE_UI_EXEC_TASK_R != head.getMsgType()) {
    printf("receive a unknow msg type, msg_type=%u\n", head.getMsgType());
    if (block) CwxMsgBlockAlloc::free(block);
    return 1;
  }
  query_msg.assign(block->rd_ptr(), block->length());
  dcmd_api::UiTaskCmdReply reply;
  if (!reply.ParseFromString(query_msg)) {
    printf("failed to parse reply-msg\n");
    if (block) CwxMsgBlockAlloc::free(block);
    return 1;
  }
  printf("task cmd result:\n");
  printf("client_id:%d\n", reply.client_msg_id());
  printf("state:%d\n", reply.state());
  if (dcmd_api::DCMD_STATE_SUCCESS != reply.state()) {
    printf("err:%s\n", reply.err().c_str());
  }
  return 0;
}

