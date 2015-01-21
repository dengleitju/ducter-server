#ifndef __DCMD_XML_PARSE_H__
#define __DCMD_XML_PARSE_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include "CwxStl.h"
#include "CwxCommon.h"
#include "CwxCharPool.h"
#include "CwxGbkUnicodeMap.h"
#include <expat.h>
#include <string.h>
#include <stdio.h>

CWINUX_USING_NAMESPACE

/**
@class XmlTreeNode
@brief 树状的数据节点对象，用于表示XML、JSON的数据节点。
*/
class XmlTreeNode
{
public:
    ///构造函数
    XmlTreeNode()
    {
        m_pChildHead = NULL;
        m_pChildTail = NULL;
        m_prev = NULL;
        m_next = NULL;
        m_pParent = NULL;
    }
    ///析构函数
    ~XmlTreeNode()
    {
        if (m_pChildHead) delete m_pChildHead;
        if (m_next) delete m_next;
    }
public:
    char*   m_szElement;///<节点的名字
    list<char*>   m_listData; ///<XML的\<aaa\>aaaaa\</aaa\>类型节点的数据
    list<pair<char*, char*> > m_lsAttrs;///<节点属性的key,value对
    XmlTreeNode* m_pChildHead;///<节点的孩子结点的头
    XmlTreeNode* m_pChildTail;///<节点的孩子结点的尾
    XmlTreeNode* m_prev;///<节点的前一个兄弟节点
    XmlTreeNode* m_next;///<节点的下一个兄弟节点
    XmlTreeNode* m_pParent;///<节点的父节点
};

/**
@class XmlParser
@brief 基于expat实现的XML流解析对象。除了支持expat默认支持的字符集，还支持GBK与gb2312字符集
*/
class XmlParser
{
public:
    enum{
        DEF_TRUCK_BUF_SIZE = 16 * 1024, ///<缺省的数据块的大小
        PATH_BUF_ALIGN = 1024 ///数据块的边界对齐大小
    };
public:
    /**
    @brief 构造函数。
    @param [in] uiBufSize 数据块的大小
    */
    XmlParser(CWX_UINT32 uiBufSize=DEF_TRUCK_BUF_SIZE);
    ///析构函数
    virtual ~XmlParser(void);
public:
    ///解析XML实体，用户可以对此API进行重载。
    virtual bool parse();
    ///expat的XML解析引擎是否初始化
    bool isReady(void) const
    {
        return m_isReady;
    }
    ///获取XML的解析错误消息
    XML_Error getLastError(void) const
    {
        return m_lastError;
    }
    ///获取XML解析的状态代码
    XML_Status getStatus(void) const
    {
        return m_status; 
    }
    ///获取XML解析的数据块
    XML_Char *getBuf(void) const
    {
        return m_szXmlBuf; 
    }
    ///获取数据块的大小
    CWX_UINT32 getBufSize(void) const
    {
        return m_uiBufSize; 
    }
    ///获取当前解析到的XML路径
    XML_Char const* getXmlPath() const
    {
        return m_szXmlPath;
    }
    ///判断XML是否为GBK的编码
    bool isGbk() const
    {
        return m_bGbk;
    }
    /**
    @brief 若字符集为GBK或gb2312，则将expat的UTF-8输出变为GBK或gb2312的编码格式。
    @param [in] value expat输出的UTF-8的字符串
    @param [in] uiValueLen value的长度
    @return 返回GBK或gb2312编码的字符串
    */
    char const* charsetValue(XML_Char const* value, CWX_UINT32 uiValueLen);
protected:
    ///设置expat的引擎的ready状态
    void setReady(bool isReady)
    {
        m_isReady = isReady;
    }
    ///设置xml解析的状态码
    void setStatus(XML_Status status)
    {
        m_status = status; 
    }
    ///设置XML解析的错误信息
    void setLastError(XML_Error lastError)
    {
        m_lastError = lastError;
    }
    ///准备xml解析的环境，继承类可以重载此API
    virtual bool prepare();
    ///获取XML解析的下一个数据块，重载类需要重载此API，为expat引擎提供数据流
    virtual ssize_t readBlock(void);
    /**
    @brief 通知进入一个XML的数据节点。
    @param [in] name XML节点的名字
    @param [in] atts XML节点的属性，atts[2n]为属性的名字，atts[2n+1]为属性的值，若atts[2n]为NULL，表示属性结束
    @return void
    */
    virtual void startElement(const XML_Char *name, const XML_Char **atts);
    /**
    @brief 通知离开一个XML的数据节点。
    @param [in] name XML节点的名字
    @return void
    */
    virtual void endElement(const XML_Char *name);
    /**
    @brief 通知一个节点内的数据。
    @param [in] s 数据的内容，其编码为UTF8的编码
    @param [in] len 数据的内容的长度。
    @return void
    */
    virtual void characterData(const XML_Char *s, int len);
    /**
    @brief 通知XML的instructions.
    @param [in] target instruction的第一个word.
    @param [in] data 第一个word后，去掉所有空格的字符串。
    @return void
    */
    virtual void processingInstruction(const XML_Char *target, const XML_Char *data);
    ///xml中的注释
    virtual void commentData(const XML_Char *data);
    ///xml的缺省数据处理句柄
    virtual void defaultHandler(const XML_Char *s, int len);
    ///通知进入XML的CDATA语法
    virtual void startCData(void);
    ///通知离开XML的CDATA语法
    virtual void endCData(void);

private:
    ///注册所有的expat的事件处理函数
    void regDefHandlers();
    ///进入一个XML节点的事件处理函数
    static XMLCALL void elementStartHandler(void *userData,
        const XML_Char *name, const XML_Char **atts);
    ///离开一个XML节点的事件处理函数
    static XMLCALL void elementEndHandler(void *userData,
        const XML_Char *name);
    ///节点内部数据的接受函数
    static XMLCALL void characterDataHandler(void *userData,
        const XML_Char *s, int len);
    ///XML instruction的接受函数
    static XMLCALL void processingInstrHandler(void *userData,
        const XML_Char *target, const XML_Char *data);
    ///注释的接受函数
    static XMLCALL void commentHandler(void *userData,
        const XML_Char *data);
    ///缺省事件的处理函数
    static XMLCALL void defaultHandler(void *userData,
        const XML_Char *s, int len);
    ///进入CDATA的事件函数
    static XMLCALL void startCDatahandler(void *userData);
    ///离开CDATA的事件函数
    static XMLCALL void endCDatahandler(void *userData);
    ///GBK、gb2312的字符集转换API
    static XMLCALL int convert(void* data, char const* s);
    ///GBK、gb2312字符编码转换的事件函数
    static XMLCALL int encodingHandler(void* userData, XML_Char const* name, XML_Encoding* info);
private:
    XML_Parser  m_expatParser;///<expat的引擎
    XML_Char *  m_szXmlBuf; ///<内部临时BUF
    CWX_UINT32  m_uiBufSize;///<临时BUF的大小
    bool    m_isReady;///<引擎初始化状态标记
    XML_Status m_status;///<XML解析的状态码
    XML_Error m_lastError;///<XML解析的错误信息
    XML_Char*  m_szXmlPath;///<当前XML节点的全路径
    CWX_UINT32 m_uiPathBufLen;///<XML节点全路径的BUF的长度
    CWX_UINT32 m_uiPathLen;///<m_szXmlPath中的节点路径长度
    bool      m_bGbk;///<是否是中文编码
    XML_Char*  m_szGbkBuf;///<进行GBK编码转换的内存
    CWX_UINT32 m_uiGbkBufLen;///<m_szGbkBuf的内存长度
};


/**
@class XmlFileParser
@brief 重载XmlParser对象，实现XML文件的流解析
*/
class  XmlFileParser : public XmlParser
{
public:
    ///构造文件，strFileName为要解析的XML的文件名
    XmlFileParser(string const& strFileName);
    ///析构函数
    virtual ~XmlFileParser();
protected:
    ///XML文件解析的准备
    virtual bool prepare();
    ///从XML文件中读取下一个待解析的数据块，-1：表示文件尾或文件读错误，通过status来识别，>=0：读取的数据的长度
    virtual ssize_t readBlock(void);
private:
    FILE *  m_fd; ///<XML文件的句柄
    string  m_strFileName;///<XML文件的名字

};

/**
@class XmlConfigParser
@brief 将XML的BUF解析成XmlTreeNode组织的节点树。除了支持expat默认支持的字符集，还支持GBK与gb2312字符集
*/
class XmlConfigParser
{
public:
    /**
    @brief 构造函数。
    @param [in] uiAvgTokenLen XML中的数据节点的平均长度
    @param [in] uiAvgXmlSize 要解析的XML的平均大小
    */
    XmlConfigParser(CWX_UINT32 uiAvgTokenLen=1024, CWX_UINT32 uiAvgXmlSize=4096);
    ///析构函数
    ~XmlConfigParser();
public:
    /**
    @brief 将szXml定义的XML文本，解析成XmlTreeNode的节点树。
    @param [in] szXml XML
    @return true：解析成功；false：解析失败
    */
    bool parse(char const* szXml);
    /**
    @brief 获取一个XML节点的属性。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @param [in] szAttr 节点的属性名
    @return NULL：不存在；否则为节点属性的数值
    */
    char const* getElementAttr(char const* szPath, char const* szAttr) const;
    /**
    @brief 获取一个XML节点的所有属性。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @param [in] attrs 节点的所有属性名，pair的first为属性名，second为属性的值
    @return false：节点不存在；否则返回节点的属性列表
    */
    bool getElementAttrs(char const* szPath, list<pair<char*, char*> >& attrs) const;
    /**
    @brief 获取[\<aa\>xxxx\</aa\>]的形式的节点的数据xxxx。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @param [in] strData 节点的数据
    @return false：节点不存在或不是\<aa\>xxxx\</aa\>的格式；节点存在而且为此格式
    */
    bool getElementData(char const* szPath, string& strData) const;
    /**
    @brief 获取节点的Tree Node。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @return NULL：节点不存在；路径的节点
    */
    XmlTreeNode const* getElementNode(char const* szPath) const;
    ///获取节点的根
    XmlTreeNode const * getRoot() const
    {
        return m_root; 
    }
    ///判断xml的编码是否为GBK
    bool isGbk() const 
    {
        return m_bGbk;
    }
private:
    /**
    @brief 通知进入一个XML的数据节点。
    @param [in] name XML节点的名字
    @param [in] atts XML节点的属性，atts[2n]为属性的名字，atts[2n+1]为属性的值，若atts[2n]为NULL，表示属性结束
    @return void
    */
    void startElement(const XML_Char *name, const XML_Char **atts);
    /**
    @brief 通知离开一个XML的数据节点。
    @param [in] name XML节点的名字
    @return void
    */
    void endElement(const XML_Char *name);
    /**
    @brief 通知一个节点内的数据。
    @param [in] s 数据的内容，其编码为UTF8的编码
    @param [in] len 数据的内容的长度。
    @return void
    */
    void characterData(const XML_Char *s, int len);
    /**
    @brief 若字符集为GBK或gb2312，则将expat的UTF-8输出变为GBK或gb2312的编码格式。
    @param [in] value expat输出的UTF-8的字符串
    @param [in] uiValueLen value的长度
    @return 返回GBK或gb2312编码的字符串
    */
    char const* charsetValue(XML_Char const* value, CWX_UINT32 uiValueLen);
private:
    ///注册所有的expat的事件处理函数
    void regDefHandlers(void);
    ///进入一个XML节点的事件处理函数
    static XMLCALL void elementStartHandler(void *userData,
        const XML_Char *name, const XML_Char **atts);
    ///离开一个XML节点的事件处理函数
    static XMLCALL void elementEndHandler(void *userData,
        const XML_Char *name);
    ///节点内部数据的接受函数
    static XMLCALL void characterDataHandler(void *userData,
        const XML_Char *s, int len);
    ///GBK、gb2312的字符集转换API
    static XMLCALL int convert(void* data, char const* s);
    ///GBK、gb2312字符编码转换的事件函数
    static XMLCALL int encodingHandler(void* userData, XML_Char const* name, XML_Encoding* info);
private:
    XML_Parser  m_expatParser;///<expat的引擎
    CwxCharPool m_memPool;///<字符内存池
    XmlTreeNode* m_root;///<根节点
    XmlTreeNode* m_pCur;///<解析过程中的当前节点
    bool      m_bGbk;///<是否为GBK编码
    XML_Char*  m_szGbkBuf;///<GBK编码转换的临时BUF
    CWX_UINT32 m_uiGbkBufLen;///<m_szGbkBuf的空间大小
};

/**
@class XmlFileConfigParser
@brief 将XML的文件解析成XmlTreeNode组织的节点树。除了支持expat默认支持的字符集，还支持GBK与gb2312字符集
*/
class  XmlFileConfigParser
{
public:
    /**
    @brief 构造函数。
    @param [in] uiAvgTokenLen XML中的数据节点的平均长度
    @param [in] uiAvgXmlSize 要解析的XML的平均大小
    */
    XmlFileConfigParser(CWX_UINT32 uiAvgTokenLen=1024, CWX_UINT32 uiAvgXmlSize=4096);
    ///析构函数
    virtual ~XmlFileConfigParser(void);
public:
    /**
    @brief 将szXml定义的XML文本，解析成XmlTreeNode的节点树。
    @param [in] strFileName XML文件名
    @return true：解析成功；false：解析失败
    */
    bool parse(string const& strFileName);
    /**
    @brief 获取一个XML节点的属性。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @param [in] szAttr 节点的属性名
    @return NULL：不存在；否则为节点属性的数值
    */
    char const* getElementAttr(char const* szPath, char const* szAttr) const 
    {
        return m_parser.getElementAttr(szPath, szAttr);
    }
    /**
    @brief 获取一个XML节点的所有属性。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @param [in] attrs 节点的所有属性名，pair的first为属性名，second为属性的值
    @return false：节点不存在；否则返回节点的属性列表
    */
    bool getElementAttrs(char const* szPath, list<pair<char*, char*> >& attrs) const
    {
        return m_parser.getElementAttrs(szPath, attrs);
    }
    /**
    @brief 获取[\<aa\>xxxx\</aa\>]的形式的节点的数据xxxx。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @param [in] strData 节点的数据
    @return false：节点不存在或不是\<aa\>xxxx\</aa\>的格式；节点存在而且为此格式
    */
    bool getElementData(char const* szPath, string& strData) const 
    {
        return m_parser.getElementData(szPath, strData);
    }
    /**
    @brief 获取节点的Tree Node。
    @param [in] szPath XML的节点，采用key:key1:key2的格式，各个节点以【:】分割
    @return NULL：节点不存在；路径的节点
    */
    XmlTreeNode const* getElementNode(char const* szPath) const 
    {
        return m_parser.getElementNode(szPath);
    }
    ///获取节点的根
    XmlTreeNode const * getRoot() const
    { 
        return m_parser.getRoot(); 
    }
    ///判断xml的编码是否为GBK
    bool isGbk() const
    { 
        return m_parser.isGbk();
    }
private:
    FILE *  m_fd;///<xml文件的FD
    string  m_strFileName;///<xml文件的名字
    char*   m_szBuf;///<XML文件读取BUF
    XmlConfigParser  m_parser;///<XmlConfigParser类，完成XML 内存的解析

};
#endif 

