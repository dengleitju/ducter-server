<?php 

/**
 * 封装请求过程
 * 
 * todo:判断连接是否存在的方法过于简单
 * todo:是否应该使用Listen?
 *
 */

class CwxRequest
{
	/**
	 * 服务器HOST
	 *
	 * @var string
	 */
    private $host;
    
    /**
     * 服务器端口
     *
     * @var integer
     */
    private $port;
    
    /**
     * socket连接
     *
     * @var resource 
     */
    private $sock;
    
    /**
     * 构造函数
     *
     * @param string $host
     * @param integer $port
     */
    public function __construct($host,$port)
    {
        $this->host = $host;
        $this->port = $port;
    }
    
    /**
     * 错误消息
     *
     * @var string
     */
    private $error;
    /**
     * 错误代码
     *
     * @var integer
     */
    private $errno;

    /**
     * 建立链接
     *
     * @return boolean
     */
    public function connect()
    {
    	//应该用更合适的方法去判断连接是否已经建立
    	if($this->sock == true){
    		return true;
    	}
    	$commonProtocol = getprotobyname("tcp");
        $socket = socket_create(AF_INET,SOCK_STREAM,$commonProtocol);
        
        if(socket_connect($socket,$this->host,$this->port)==false){
        	$this->errno = ERR_REQUEST_CONNECT_FAILED;
        	$this->error = "建立连接失败[{$this->host}:{$this->port}]";
        	return false;
        }
        $this->sock = $socket;
        return true;
    }
    
    /**
     * 断开连接
     *
     */
    public function close()
    {
        if($this->sock == true){
    		socket_close($this->sock);
    		$this->sock = null;
    	}
    }
        
    /**
     * 发送请求，并返回获得的消息体
     *
     * @param package $package
     * @return string or false 
     */
    public function request($package)
    {   
        if($this->sendMsg($package) == true){
            return $this->receiveMsg();
        }
    }
    
    /**
     * 发送消息体
     *
     * @param package $package
     * @return boolean
     */
    public function sendMsg($package)
    {
        if(strlen($package) == 0){
            $this->errno = ERR_REQUEST_NULL_PACKAGE;
            $this->error = '消息体不能为空串';
            return false;
        }
        $socket = $this->sock;
        if($socket == null){
            $this->errno = ERR_REQUEST_NULL_SOCKET;
            $this->error = '连接不存在，请检查连接是否正常';
            return false;
        }
        $data = socket_write($socket,$package);
        if($data === false){
            $this->errno = ERR_REQUEST_SEND_FAILED;
            $this->error = '发送消息失败，请检查连接是否正常';
            return false;
        }
        else{
            return true;
        }
    }
    
    /**
     * 接收消息体
     *
     * @param package $package
     * @return string or boolean
     */
    public function receiveMsg()
    {   
        $socket = $this->sock;
        if($socket == null){
            $this->errno = ERR_REQUEST_NULL_SOCKET;
            $this->error = '连接不存在，请检查连接是否正常';
            return false;
        }
        
        $rdata = socket_read($socket,14,PHP_BINARY_READ );       
        $n = strlen($rdata);
        if($n == 14){
        	$header = new CwxMsgHead();
        	$ret = $header->fromNet($rdata);        	
        	if($ret == true){
        		
        		$dataLen = $header->getDataLen(); 
        		
        		$rdata = null;
        		$n = 0;
        		
        		while( $n < $dataLen){
        			$rt = socket_read($socket,$dataLen-$n,PHP_BINARY_READ);
        			$rdata .= $rt;
        			$n = $n+strlen($rt); 
        		}
        		
        		if($n == $dataLen){
        			//处理压缩的消息，进行解压缩操作
        			if( ($header->getAttr() & 2) == true){
        				$rdata = gzuncompress($rdata);
        			}
        			return $rdata;
        		}
        		else{
        			$this->errno = ERR_REQUEST_RECEIVE_BAD_MSG;
        			$this->error = '获取消息体失败 返回数据长度错误';
        			return false;	
        		}
        	}
        	else{
        		$this->errno = $header->getLastErrno();
        		$this->error = $header->getLastError();
        		return false;	
        	}
        }
        else{
        	$this->errno = ERR_REQUEST_RECEIVE_BAD_MSG_HEADR;
        	$this->error = '获取消息头失败 返回数据长度错误';
        	return false;
        }
    }
    
     /**
     * 获取最后的错误信息
     *
     * @return string
     */
    public function getLastError(){
    	return $this->error;
    }
    
    /**
     * 获取最后的错误代码
     *
     * @return integer
     */
    public function getLastErrno(){
    	return $this->errno;
    }
   
}


?>
