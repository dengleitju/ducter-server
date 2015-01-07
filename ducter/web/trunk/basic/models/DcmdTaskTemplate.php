<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task_template".
 *
 * @property integer $task_tmpt_id
 * @property string $task_tmpt_name
 * @property integer $task_cmd_id
 * @property string $task_cmd
 * @property integer $svr_id
 * @property string $svr_name
 * @property integer $app_id
 * @property integer $update_env
 * @property integer $concurrent_rate
 * @property integer $timeout
 * @property integer $process
 * @property integer $auto
 * @property string $task_arg
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */

function xml_to_array( $xml )
{//array(1) { ["env"]=> array(2) { ["name"]=> string(2) "gu" ["aa"]=> string(4) "miao" } } 
   $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
   if(preg_match_all($reg, $xml, $matches))
   {
      $count = count($matches[0]);
      $arr = array();
      for($i = 0; $i < $count; $i++)
      {
         $key = $matches[1][$i];
         $val = xml_to_array( $matches[2][$i] );  // 递归
         if(array_key_exists($key, $arr))
         {
            if(is_array($arr[$key]))
            {
              if(!array_key_exists(0,$arr[$key]))
              {
                $arr[$key] = array($arr[$key]);
              }
            }else{
              $arr[$key] = array($arr[$key]);
            }
            $arr[$key][] = $val;
         }else{
           $arr[$key] = $val;
         }
      }
      return $arr;
   }else{
      return $xml;
   }
}

function xmltoarray( $xml )
{
        if($xml){
                $arr = xml_to_array($xml);
                $key = array_keys($arr);
                return $arr[$key[0]];
        }else{
                return '';
        }

}

class DcmdTaskTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_tmpt_name', 'task_cmd_id', 'task_cmd', 'svr_id', 'svr_name', 'app_id', 'update_env', 'concurrent_rate', 'timeout', 'process', 'auto', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_cmd_id', 'svr_id', 'app_id', 'update_env', 'concurrent_rate', 'timeout', 'process', 'auto', 'opr_uid'], 'integer'],
            [['task_arg'], 'string'],
            [['utime', 'ctime'], 'safe'],
            [['task_tmpt_name', 'svr_name'], 'string', 'max' => 128],
            [['task_cmd'], 'string', 'max' => 64],
            [['comment'], 'string', 'max' => 512],
            [['task_tmpt_name'], 'unique']
        ];
    }

    public function getAppName($app_id) {
      $query = DcmdApp::findOne($app_id);
      if($query) return $query['app_name'];
      return "";
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_tmpt_id' => 'Task Tmpt ID',
            'task_tmpt_name' => 'Task Tmpt Name',
            'task_cmd_id' => 'Task Cmd ID',
            'task_cmd' => 'Task Cmd',
            'svr_id' => 'Svr ID',
            'svr_name' => 'Svr Name',
            'app_id' => 'App ID',
            'update_env' => 'Update Env',
            'concurrent_rate' => 'Concurrent Rate',
            'timeout' => 'Timeout',
            'process' => 'Process',
            'auto' => 'Auto',
            'task_arg' => 'Task Arg',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }

    public function parseArg($arg) {
       $ar =  xmltoarray($arg);
       $retcontent = "";
       foreach($ar as $k=>$v) $retcontent .= $k."=".$v." ; ";
       return $retcontent;
    }
}
