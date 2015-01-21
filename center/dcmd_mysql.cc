#include "dcmd_mysql.h"

bool Mysql::init(){
    disconnect();
    mysql_init(&m_handle);
    m_bInit = true;
    return true;
}

bool Mysql::connect(char const* szSvrName,
                         char const* szUser,
                         char const* szPasswd,
                         char const* szDbName,
                         unsigned short unPort,
                         char const* szCharset,
                         char const* szUnixsock,
                         unsigned long uiClientFlag,
                         unsigned long uiTimeoutSecond)
{
    if (m_bConnected) disconnect();
    if (!m_bInit) mysql_init(&m_handle);
    mysql_options(&m_handle, MYSQL_SET_CHARSET_NAME, szCharset);
    m_bInit = true;
    if (uiTimeoutSecond){
        mysql_options(&m_handle, MYSQL_OPT_CONNECT_TIMEOUT, (const char *)&uiTimeoutSecond);
    }
    if (!mysql_real_connect(&m_handle, 
        szSvrName,
        szUser,
        szPasswd,
        szDbName,
        unPort,
        szUnixsock,
        uiClientFlag)){
            this->m_strErrMsg = "Couldn't connect to engine! error:";
            this->m_strErrMsg += mysql_error(&m_handle);
            return false;
    }
    this->m_bConnected = true;
    return true;
}

void Mysql::disconnect(){
    if (m_result) mysql_free_result(m_result);
    m_result = NULL;
    if (m_bConnected) mysql_close(&m_handle);
    m_bConnected = false;
    m_bInit = false;
    m_iAffectedRow = 0;
}

bool Mysql::setCharacterSet(char const* szCharsetName){
    return mysql_set_character_set(&m_handle, szCharsetName)?false:true;
}
void Mysql::setOption(mysql_option option, const char *arg){
    mysql_options(&m_handle, option, arg);
}
bool Mysql::setAutoCommit(bool bAutoCommit){
    if (0 == mysql_autocommit(&m_handle, bAutoCommit?1:0)) return true;
    this->m_strErrMsg = "Failure to set autocommit sign, error:";
    this->m_strErrMsg += mysql_error(&m_handle);
    return false;
}
bool Mysql::commit(){
    if (0 == mysql_commit(&m_handle)) return true;
    this->m_strErrMsg = "Failure to commit, error: ";
    this->m_strErrMsg += mysql_error(&m_handle);
    return false;

}
bool Mysql::rollback(){
    if (0 == mysql_rollback(&m_handle)) return true;
    this->m_strErrMsg = "Failure to rollback, error: ";
    this->m_strErrMsg += mysql_error(&m_handle);
    return false;
}

int Mysql::execute(char const* szSql){
    if (m_result) mysql_free_result(m_result);
    m_result = NULL;
    int iRet = mysql_query(&m_handle, szSql);
    if (0 != iRet){
        this->m_strErrMsg = "Failure to execute sql, error:";
        this->m_strErrMsg += mysql_error(&m_handle);
        this->m_strErrMsg += "  Sql:";
        this->m_strErrMsg += szSql;
        if (mysql_ping(&m_handle)) this->disconnect();
        return -1;
    }
    this->m_iAffectedRow = (int)mysql_affected_rows(&m_handle);
    return this->m_iAffectedRow;
}

///获取sql的count数量,-1表示失败
bool Mysql::count(char const* szSql, CWX_INT64& num){
    if (!query(szSql)){
        freeResult();
        return false;
    }
    bool bNull = false;
    char const* szValue;
    num = 0;
    while(next()){
        //fetch host
        szValue = fetch(0, bNull);
        if (bNull) break;
        num = strtoll(szValue, NULL, 10);
        break;
    }
    freeResult();
    return true;
}


bool Mysql::query(char const* szSql){
    return query(szSql, strlen(szSql));
}
bool Mysql::query(char const* szSql, unsigned long uiSqlLen){
    if (m_result) mysql_free_result(m_result);
    m_result = NULL;
    int iRet = mysql_real_query(&m_handle, szSql,  uiSqlLen);
    if (0 != iRet){
        this->m_strErrMsg = "Failure to execute sql, error:";
        this->m_strErrMsg += mysql_error(&m_handle);
        this->m_strErrMsg += "  Sql:";
        this->m_strErrMsg += szSql;
        if (mysql_ping(&m_handle)) this->disconnect();
        return false;
    }
    if (!(this->m_result =mysql_use_result(&m_handle))){
        this->m_strErrMsg = "Can't get result, error:";
        this->m_strErrMsg += mysql_error(&m_handle);
        this->m_strErrMsg += "  Sql:";
        this->m_strErrMsg += szSql;
        if (mysql_ping(&m_handle)) this->disconnect();
        return false;
    }
    return true;
}
///-1:failure; 0: finish; 1: get one row
int	 Mysql::next(){
    int iRet = 1;
    if (!m_result) return 0;
    m_row = mysql_fetch_row(m_result);
    if (!m_row){
        iRet = mysql_errno(&m_handle)?-1:0;
        mysql_free_result(m_result);
        m_result = NULL;
    }
    return iRet;
}
char const* Mysql::fetch(unsigned long uiFieldIndex, bool& bNull){
    bNull = true;
    if (this->m_row){
        if (NULL == this->m_row[uiFieldIndex]){
            return "";
        }else{
            bNull = false;
            return this->m_row[uiFieldIndex];
        }
    }
    return "";
}
char const* Mysql::getFieldName(unsigned long uiFieldIndex){
    MYSQL_FIELD* field = getFieldInfo(uiFieldIndex);
    if (field) return field->name;
    return "";
}
MYSQL_FIELD* Mysql::getFieldInfo(unsigned long uiFieldIndex){
    if (!m_result) return NULL;
    if (mysql_field_seek(m_result, uiFieldIndex) != uiFieldIndex) return NULL;
    return mysql_fetch_field(m_result);
}

int Mysql::getFieldNum(){
    if (m_result){
        return mysql_num_fields(this->m_result);
    }
    m_strErrMsg = "No query result.";
    return -1;
}

int Mysql::freeResult(){
    if (m_result) mysql_free_result(m_result);
    m_result = NULL;
    return 0;
}
bool Mysql::ping(){
    return mysql_ping(&m_handle)?false:true;
}

