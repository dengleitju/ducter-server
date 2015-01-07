<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task".
 *
 * @property integer $task_id
 * @property string $task_name
 * @property string $task_cmd
 * @property integer $depend_task_id
 * @property string $depend_task_name
 * @property integer $svr_id
 * @property string $svr_name
 * @property string $svr_path
 * @property string $tag
 * @property integer $update_env
 * @property integer $update_tag
 * @property integer $state
 * @property integer $freeze
 * @property integer $valid
 * @property integer $pause
 * @property string $err_msg
 * @property integer $concurrent_rate
 * @property integer $timeout
 * @property integer $auto
 * @property integer $process
 * @property string $task_arg
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_name', 'task_cmd', 'depend_task_id', 'depend_task_name', 'svr_id', 'svr_name', 'svr_path', 'tag', 'update_env', 'update_tag', 'state', 'freeze', 'valid', 'pause', 'err_msg', 'concurrent_rate', 'timeout', 'auto', 'process', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['depend_task_id', 'svr_id', 'update_env', 'update_tag', 'state', 'freeze', 'valid', 'pause', 'concurrent_rate', 'timeout', 'auto', 'process', 'opr_uid'], 'integer'],
            [['task_arg'], 'string'],
            [['utime', 'ctime'], 'safe'],
            [['task_name', 'depend_task_name', 'svr_name', 'tag'], 'string', 'max' => 128],
            [['task_cmd'], 'string', 'max' => 64],
            [['svr_path'], 'string', 'max' => 256],
            [['err_msg', 'comment'], 'string', 'max' => 512],
            [['task_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'task_name' => 'Task Name',
            'task_cmd' => 'Task Cmd',
            'depend_task_id' => 'Depend Task ID',
            'depend_task_name' => 'Depend Task Name',
            'svr_id' => 'Svr ID',
            'svr_name' => 'Svr Name',
            'svr_path' => 'Svr Path',
            'tag' => 'Tag',
            'update_env' => 'Update Env',
            'update_tag' => 'Update Tag',
            'state' => 'State',
            'freeze' => 'Freeze',
            'valid' => 'Valid',
            'pause' => 'Pause',
            'err_msg' => 'Err Msg',
            'concurrent_rate' => 'Concurrent Rate',
            'timeout' => 'Timeout',
            'auto' => 'Auto',
            'process' => 'Process',
            'task_arg' => 'Task Arg',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
