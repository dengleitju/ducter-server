<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task_cmd_arg".
 *
 * @property integer $id
 * @property integer $task_cmd_id
 * @property string $task_cmd
 * @property string $arg_name
 * @property integer $optional
 * @property integer $arg_type
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdCmdArg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_cmd_arg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_cmd_id', 'task_cmd', 'arg_name', 'optional', 'arg_type', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_cmd_id', 'optional', 'arg_type', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['task_cmd'], 'string', 'max' => 64],
            [['arg_name'], 'string', 'max' => 32],
            [['task_cmd_id', 'arg_name'], 'unique', 'targetAttribute' => ['task_cmd_id', 'arg_name'], 'message' => 'The combination of Task Cmd ID and Arg Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_cmd_id' => 'Task Cmd ID',
            'task_cmd' => 'Task Cmd',
            'arg_name' => 'Arg Name',
            'optional' => 'Optional',
            'arg_type' => 'Arg Type',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
