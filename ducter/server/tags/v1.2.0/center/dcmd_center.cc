#include <CwxAppProcessMgr.h>
#include "dcmd_center_app.h"

int main(int argc, char** argv){
    //创建center的app对象实例
    dcmd::DcmdCenterApp* app = new dcmd::DcmdCenterApp();
    //初始化双进程管理器
    if (0 != CwxAppProcessMgr::init(app)) return 1;
    //启动双进程，一个为监控center进程的监控进程
    //一个为提供center服务的工作进程。
    CwxAppProcessMgr::start(argc, argv, 120, 300);
    return 0;
}

