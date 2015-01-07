<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");
/**
 * This is the model class for table "dcmd_service_pool_node".
 *
 * @property integer $id
 * @property integer $svr_pool_id
 * @property integer $svr_id
 * @property integer $nid
 * @property integer $app_id
 * @property string $ip
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdServicePoolNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool_node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_pool_id', 'svr_id', 'nid', 'app_id', 'ip', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['svr_pool_id', 'svr_id', 'nid', 'app_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['ip'], 'string', 'max' => 16],
            [['svr_pool_id', 'ip'], 'unique', 'targetAttribute' => ['svr_pool_id', 'ip'], 'message' => 'The combination of Svr Pool ID and Ip has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'svr_pool_id' => 'Svr Pool ID',
            'svr_id' => 'Svr ID',
            'nid' => 'Nid',
            'app_id' => 'App ID',
            'ip' => 'Ip',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }

   public function getAppAlias($app_id)
   {
     $query = DcmdApp::findOne($app_id);
     if($query) return $query->app_alias;
     return "";
   }
  
   public function getServiceAlias($svr_id)
   {
     $query = DcmdService::findOne($svr_id);
     if($query) return $query->svr_alias;
     return "";
   }

   public function getServicePoolName($svr_pool_id)
   {
     $query =  DcmdServicePool::findOne($svr_pool_id);
     if($query) return $query->svr_pool;
     return "";
   }
    public function getAgentState($ip)
    {
      $query = DcmdCenter::findOne(['master'=>1]);

      if ($query) {
         list($host, $port) = split(':', $query["host"]);
         $agent_info = getAgentInfo($host, $port, array($ip), 1);
         if($agent_info->getState() == 0) {
          foreach($agent_info->getAgentinfo() as $agent) {
            if($agent->getState() == 3) return "连接";
            return "未连接";
          }
         }
       }
       return "未连接";
    }


}
