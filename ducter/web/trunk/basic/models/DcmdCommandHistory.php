<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_command_history".
 *
 * @property integer $cmd_id
 * @property integer $task_id
 * @property integer $subtask_id
 * @property string $svr_pool
 * @property integer $svr_pool_id
 * @property string $svr_name
 * @property string $ip
 * @property integer $cmd_type
 * @property integer $state
 * @property string $err_msg
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdCommandHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_command_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'subtask_id', 'svr_pool', 'svr_pool_id', 'svr_name', 'ip', 'cmd_type', 'state', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_id', 'subtask_id', 'svr_pool_id', 'cmd_type', 'state', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['svr_pool', 'svr_name'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 16],
            [['err_msg'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cmd_id' => 'Cmd ID',
            'task_id' => 'Task ID',
            'subtask_id' => 'Subtask ID',
            'svr_pool' => 'Svr Pool',
            'svr_pool_id' => 'Svr Pool ID',
            'svr_name' => 'Svr Name',
            'ip' => 'Ip',
            'cmd_type' => 'Cmd Type',
            'state' => 'State',
            'err_msg' => 'Err Msg',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
