<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_task_node_history".
 *
 * @property integer $subtask_id
 * @property integer $task_id
 * @property string $task_cmd
 * @property string $svr_pool
 * @property string $svr_name
 * @property string $ip
 * @property integer $state
 * @property integer $ignored
 * @property string $start_time
 * @property string $finish_time
 * @property string $process
 * @property string $err_msg
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdTaskNodeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_node_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'task_cmd', 'svr_pool', 'svr_name', 'ip', 'state', 'ignored', 'start_time', 'finish_time', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_id', 'state', 'ignored', 'opr_uid'], 'integer'],
            [['start_time', 'finish_time', 'utime', 'ctime'], 'safe'],
            [['task_cmd', 'svr_pool', 'svr_name'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 16],
            [['process'], 'string', 'max' => 32],
            [['err_msg'], 'string', 'max' => 512],
            [['task_id', 'ip', 'svr_pool'], 'unique', 'targetAttribute' => ['task_id', 'ip', 'svr_pool'], 'message' => 'The combination of Task ID, Svr Pool and Ip has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subtask_id' => 'Subtask ID',
            'task_id' => 'Task ID',
            'task_cmd' => 'Task Cmd',
            'svr_pool' => 'Svr Pool',
            'svr_name' => 'Svr Name',
            'ip' => 'Ip',
            'state' => 'State',
            'ignored' => 'Ignored',
            'start_time' => 'Start Time',
            'finish_time' => 'Finish Time',
            'process' => 'Process',
            'err_msg' => 'Err Msg',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
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
