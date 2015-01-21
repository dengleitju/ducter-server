#include <CwxAppProcessMgr.h>
#include "dcmd_agent_app.h"
int main(int argc, char** argv){
    //创建agent的app对象实例
    dcmd::DcmdAgentApp* pApp = new dcmd::DcmdAgentApp();
    //初始化双进程管理器
    if (0 != CwxAppProcessMgr::init(pApp)) return 1;
    //启动双进程，一个为监控agent进程的监控进程，
    //一个为提供agent服务的工作进程。
    CwxAppProcessMgr::start(argc, argv, 200, 300);
    return 0;
}

