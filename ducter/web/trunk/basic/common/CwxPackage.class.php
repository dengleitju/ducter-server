<?php 

/**
 * 对Key/Value的消息体进行打包解包处理
 * 
 * todo:同名Key如何处理
 * todo:对签名没有进行校验其是否正确
 * todo:对非array、非string的数据类型没有进行处理，整数类型直接转成字符串给打包了。
 */

class CwxPackage
{  
	/**
	 * 最新的错误消息
	 *
	 * @var string
	 */
	private static $error;
	/**
	 * 最新的错误代码
	 *
	 * @var integer
	 */
	private static $errno;
	
	/**
	 * 将一个Key/value的消息体解包成一个数组
	 *
	 * @param string $msg
	 * @return array
	 */
    public static function unPack($msg)
    {
        $result = array();
        if( $msg === null || $msg === ''){
        	return $result;
        }
        else if( is_string($msg) == false){
        	self::$errno = ERR_PACKAGE_UNPACK_BAD_PARAM_TYPE;
        	self::$error = '参数应该是NULL或字符串';
        	return false;
        }
        
        while( strlen($msg) > 0) {
        	
        	if( strlen($msg) < 3 ){
        		self::$errno = ERR_PACKAGE_UNPACK_FAILED_A;
        		self::$error = '消息体错误，解包失败';
        		return false;
        	}
        	$package = unpack('NkvLen/nkeyLen',$msg);
            
            if( is_array( $package ) == true){
            	
                $kvLen = $package['kvLen'];
                //处理value为array的情况
                if( ($kvLen & 0x80000000) == true){
                    $isLoop = true;
                    $kvLen = $kvLen & 0x7fffffff;
                }
                               
                $keyLen = $package['keyLen'];
                $valueLen = $kvLen - 8 - $keyLen;
                $msg = substr($msg,6);
                
                if( strlen($msg) < $keyLen + $valueLen + 2 ){
                	self::$errno = ERR_PACKAGE_UNPACK_FAILED_B;
        			self::$error = '消息体错误，解包失败2';
        			return false;
                }
                
                //$package = unpack("A{$keyLen}key/atemp1/A{$valueLen}value/atemp2",$msg);
                //$key = $package['key'];
                //$value = $package['value'];
                
                //$package = unpack("A{$keyLen}key/",$msg);
                //$key = $package['key'];
                //$value = $package['value'];
                
                //因为pack函数的效率比较低，因此这里进行尽量避开pack操作。
                if($msg[$keyLen] != "\0" || $msg[$keyLen+$valueLen+1] !="\0"){
                    self::$errno = ERR_PACKAGE_UNPACK_FAILED_C;
        			self::$error = '消息体错误，解包失败3';
        			return false;
                }                
                $key    =   substr($msg,0,$keyLen);
                $value  = substr($msg,$keyLen+1,$valueLen);
                
                flush();
                //exit;
                if($isLoop == true){
                	$value = self::unPack($value);
                    if($value === false){
                    	return false;
                    }
                }
                $msg = substr($msg,$kvLen-6);
                
                //对于chunk消息来说，其存在同名key,因此这里作了特殊处理。
                if($key == 'm'){
                	$result[] = $value;
                }
                else{
                	$result[$key] = $value;
                }
            }
            else{
                return false;
            }
        }
        return $result;
    }

    /**
     * 将一个数组打包成一个Key/Value的消息体
     *
     * @param array $msg
     * @return string
     */
    public static function toPack($msg)
    {
    	$content = null;
    	
    	if(is_array($msg) == true){
    		foreach($msg as $key => $value){    			
    			//这里是个约定，当value为null时，抛弃key.
    			//因此，需要一个空key时候，请将value设置空字符串
    			if($value === null){
    				continue;
    			}
    			//对value是数组的情况进行处理
    			if(is_array($value) == true){
    				$value = self::toPack($value);
    				$keyvalue_len = strlen($key)+strlen($value)+2+6;
    				$keyvalue_len = $keyvalue_len | 0x80000000;
    			}
    			else{
    				$keyvalue_len = strlen($key)+strlen($value)+2+6;
    			}
    			$content .= pack("Nn",$keyvalue_len,strlen($key)).$key."\0".$value."\0";
    		}
    		return $content;
    	}
    	else if(is_null($msg) == true){
    		return $content;
    	}
    	else{
    		self::$errno = ERR_PACKAGE_PACK_BAD_PARAM_TYPE;
    		self::$error = '参数应该是null或数组';
    		return false;	
    	}
    }
    
    /**
     * 获得最近的错误代码
     *
     * @return integer
     */
    public static function getLastErrno()
    {
    	return self::$errno;
    }
    
    /**
     * 获得最近的错误消息
     *
     * @return string
     */
    public static function getLastError()
    {
    	return self::$error;
    }
    
    /**
     * 获取与一个整数等价的二进制串
     *
     * @param integer $num
     */
    public static function intToBuff($num)
    {
    	$ret = pack('L',$num);
    	return $ret;
    }
    
    /**
     * 将一块内存用16进制表示出来。
     *
     * @param string $buff
     * @return string
     */
    public static function buffToAscii($buff){
    	$result = null;
    	for($i=0;$i<strlen($buff);$i++){
    		$ord = ord($buff[$i]);
    		$result .= dechex($ord); 
    	}
    	return $result;
    }
    
}

?>
