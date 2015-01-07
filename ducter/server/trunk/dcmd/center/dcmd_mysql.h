#ifndef __DCMD_MYSQL_H__
#define __DCMD_MYSQL_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
/**
@file dcmd_mysql.h
@brief Mysql数据库的接口类Mysql。
@author cwinux@gmail.com
@version 0.1
@date 2011-11-10
@warning
@bug
*/
#include "CwxGlobalMacro.h"
#include "CwxHostInfo.h"
#include "CwxCommon.h"
#include "CwxStl.h"
#include "CwxStlFunc.h"
#include "CwxLogger.h"
#include "CwxTss.h"
#include "dcmd_macro.h"

#include <mysql.h>

/**
@class Mysql
@brief Mysql数据库的连接对象。
*/
class CWX_API Mysql{
public:
    ///构造函数
    Mysql(){
        m_bInit = false;
        m_bConnected = false;
        m_iAffectedRow = 0;
        m_result = NULL;
    }
    ///析构函数
    ~Mysql(){
        disconnect();
    }
public:
    ///初始化数据库连接对象
    bool init();
    /**
    @brief 连接MYSQL数据库。
    @param [in] szSvrName 数据库的服务器
    @param [in] szUser 数据库连接的用户名
    @param [in] szPasswd 用户口令
    @param [in] szDbName 连接数据库名字
    @param [in] unPort 连接的端口号，默认为3306
    @param [in] szUnixsock 若是采用UNIX DOMAIN，则指定其sock文件
    @param [in] uiClientFlag 连接的属性，缺省为CLIENT_MULTI_STATEMENTS|CLIENT_FOUND_ROWS
    @param [in] uiTimeoutSecond 连接的超时时间，默认为阻塞连接
    @return false：连接失败；true：连接成功。在失败的情况下，getErrMsg()获取错误信息。
    */
    bool connect(char const* szSvrName,
                 char const* szUser,
                 char const* szPasswd,
                 char const* szDbName,
                 unsigned short unPort = 3306,
                 char const* szCharset = "utf8",
                 char const* szUnixsock = NULL,
                 unsigned long uiClientFlag = CLIENT_MULTI_STATEMENTS|CLIENT_FOUND_ROWS,
                 unsigned long uiTimeoutSecond=0);
    ///关闭连接
    void disconnect();
    ///设置连接的字符集，若需要设置，必须在init()后设定。
    bool setCharacterSet(char const* szCharsetName);
    ///设置连接的属性，若需要设置，必须在init()后设定。
    void setOption(mysql_option option, const char *arg);
    ///设置自动提交模式
    bool setAutoCommit(bool bAutoCommit);
    ///提交数据的修改，只是非自动提交的模式下有小
    bool commit();
    ///回滚对数据库的修改
    bool rollback();
    ///执行非query的数据库操作，返回值：-1：失败，>=0：操作影响的数据记录数量
    int  execute(char const* szSql);
    ///获取sql的count数量,-1表示失败
    bool count(char const* szSql, CWX_INT64& num);
    ///执行数据查询操作。false：查询失败；true：查询成功
    bool query(char const* szSql);
    ///执行binary sql的查询。false：查询失败；true：查询成功
    bool query(char const* szSql, unsigned long uiSqlLen);
    ///获取query结果集的下一条记录，返回值：-1:failure; 0: finish; 1: get one row
    int	 next();
    ///获取当前记录的第uiFieldIndex个字段,并通过bNull返回其是否为NULL。
    char const*  fetch(unsigned long uiFieldIndex, bool& bNull);
    ///获取当前记录的第uiFieldIndex个字段的名字。
    char const* getFieldName(unsigned long uiFieldIndex);
    ///获取当前记录的第uiFieldIndex个字段的信息。
    MYSQL_FIELD* getFieldInfo(unsigned long uiFieldIndex);
    ///获取当前结果集的字段数量
    int	 getFieldNum();
    ///获取前一个数据库操作影响的数据库记录数量
    int  getAffectedRow() const { return m_iAffectedRow;}
    ///释放当前的查询结果集
    int	 freeResult();
    ///检查数据库连接是否有效
    bool ping();
    ///在数据库操作失败的情况下，获取失败的错误信息
    char const* getErrMsg() const { return m_strErrMsg.c_str();}
    ///检查是否建立了数据库连接
    bool IsConnected() const {return m_bConnected;}
    ///获取MYSQL的连接句柄
    MYSQL& getHandle() { return m_handle;}
private:
    MYSQL	    m_handle;///<mysql的链接句柄
    MYSQL_RES*  m_result;///<查询操作返回的结果集
    MYSQL_ROW   m_row;///<结果集的当前行
    bool        m_bInit;///<对象是否初始化
    bool	    m_bConnected;///<数据库的链接是否建立
    long       m_iAffectedRow;///<数据库操作影响的数据库的记录数量
    string	    m_strErrMsg;///<操作失败时的错误信息
};

#endif

