<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task_cmd".
 *
 * @property integer $task_cmd_id
 * @property string $task_cmd
 * @property string $script_md5
 * @property integer $timeout
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdTaskCmd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_cmd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_cmd', 'ui_name', 'script_md5', 'timeout', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['timeout', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['task_cmd'], 'string', 'max' => 64],
            [['script_md5'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 512],
            [['task_cmd'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_cmd_id' => 'Task Cmd ID',
            'ui_name' => 'ui_name',
            'task_cmd' => 'Task Cmd',
            'script_md5' => 'Script Md5',
            'timeout' => 'Timeout',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
