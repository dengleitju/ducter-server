<?php 

/**
 * 处理Cwinux消息头
 *
 * todo:没有对construct和setter函数的参数进行合法性检查；比如，datelen不能大于2^31,attribute不能大于8位。
 * todo:没有对打包和解包进行比较全面的容错处理
 */

class CwxMsgHead
{
	/**
	 * 消息格式版本号
	 *
	 * @var int8
	 */
    private $version;
    /**
     * 消息类型
     *
     * @var int16
     */
    private $msgType;
    /**
     * 消息体的长度
     *
     * @var int32
     */
    private $dataLen;
    /**
     * 消息属性
     *
     * @var int8
     */
    private $attribute;
    /**
     * 任务ID
     *
     * @var int32
     */
    private $taskId;
    
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
     * 构造函数
     *
     * @param int16 $msgType
     * @param int32 $dataLen
     * @param int32 $taskId
     * @param int8 $attibute
     * @param int8 $version
     */
    function __construct($msgType=0,$dataLen=0,$taskId=0,$attibute=0,$version=0)
    {
        $this->version    =   $version;
        $this->msgType    =   $msgType;
        $this->dataLen    =   $dataLen;
        $this->attribute  =   $attibute;
        $this->taskId     =   $taskId;
    }

    /**
     * 把消息头打包
     *
     * @return string 打包后的二进制串
     */
    public function toNet()
    {
    	if($this->msgType == 0){
    		$this->error = "消息类型不能为0或空";
    		$this->errno = ERR_HEADER_BAD_MSG_TYPE;
    		return false;
    	}
    	//计算校验和
        $checkSum = ($this->version + $this->attribute + $this->msgType + $this->taskId + $this->dataLen) & 0xffff;
        //打包
        $binaryData = pack('CCnNNn',$this->version,$this->attribute,$this->msgType,$this->taskId,$this->dataLen,$checkSum);
        
        return $binaryData;
    }
    
    /**
     * 把二进制串解成一个消息头
     * 
     * @param string $data
     * @return CwxMsgHead
     */
    public function fromNet($data)
    {
    	if(strlen($data) < 1+1+2+4+4+2 ){
    		$this->errno = ERR_HEADER_BAD_HEAD_LENGTH;
    		$this->error = "待解包的消息头长度错误";
    		return false;
    	}
        $package = unpack('Cversion/Cattribute/nmsgType/NtaskId/NdataLen/ncheckSum',$data);
        if( is_array($package) == true){
        	
        	if( (($package['msgType'] + $package['dataLen'] + $package['taskId'] + $package['attribute'] + $package['version']) & 0xffff)
        	!= $package['checkSum'] ){
        		$this->errno = ERR_HEADER_BAD_CHECK_SUM;
        		$this->error = "校验码不匹配";
        		return false;
        	}
        	else{
        		$this->msgType = $package['msgType'];
        		$this->dataLen = $package['dataLen'];
        		$this->taskId  = $package['taskId'];
        		$this->attribute = $package['attribute'];
        		$this->version	= $package['version'];
        		return true;
        	}
        }
        else{
        	$this->errno = ERR_HEADER_UNPACK_FAILED;
    		$this->error = "解包失败";
        	return false;
        }
    }

    /**
     * 是否为系统消息
     *
     * @return boolean
     */
    public function isSysMsg()
    {
        return ($this->attribute & 0x80)>0 ? true : false;
    }

    /**
     * 获取消息体长度
     *
     * @return integer
     */
    public function getDataLen()
    {
        return $this->dataLen;
    }

    /**
     * 设置消息体长度
     *
     * @param integer $dataLen
     */
    public function setDataLen($dataLen)
    {
        $this->dataLen = $dataLen;
    }

    /**
     * 设置任务ID
     *
     * @param integer $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * 获取任务ID
     *
     * @return integer
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * 获取消息类型
     *
     * @return integer
     */
    public function getMsgType()
    {
        return $this->msgType;
    }

    /**
     * 设置消息类型
     *
     * @param integer $msgType
     */
    public function setMsgType($msgType)
    {
        $this->msgType = $msgType;
    }

    /**
     * 获取消息版本号
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * 设置消息版本号
     *
     * @param integer $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * 设置消息属性
     *
     * @param integer $attr
     */
    public function setAttr($attr)
    {
        $this->attribute = $attr;
    }

    /**
     * 获取消息属性
     *
     * @return integer
     */
    public function getAttr()
    {
        return $this->attribute;
    }

    /**
     * 是否具有某些属性
     *
     * @param integer $attr
     * @return boolean
     */
    public function isAttr($attr)
    {
        return ($this->attribute & $attr) == $attr ? true : false;
    }
    
    /**
     * 增加某些属性
     *
     * @param integer $attr
     */
    public function addAttr($attr)
    {
        $this->attribute = $this->attribute | $attr;
    }
    
    /**
     * 取消某些属性
     *
     * @param integer $attr
     */
    public function clrAttr($attr)
    {
        $this->attribute = $this->attribute & ~$attr;
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
