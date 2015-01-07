<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task_service_pool_history".
 *
 * @property integer $id
 * @property integer $task_id
 * @property string $task_cmd
 * @property string $svr_pool
 * @property integer $svr_pool_id
 * @property string $env_ver
 * @property string $repo
 * @property string $run_user
 * @property integer $undo_node
 * @property integer $doing_node
 * @property integer $finish_node
 * @property integer $fail_node
 * @property integer $ignored_fail_node
 * @property integer $ignored_doing_node
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdTaskServicePoolHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_service_pool_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'task_cmd', 'state', 'svr_pool', 'svr_pool_id', 'env_ver', 'repo', 'run_user', 'undo_node', 'doing_node', 'finish_node', 'fail_node', 'ignored_fail_node', 'ignored_doing_node', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_id', 'svr_pool_id', 'undo_node', 'doing_node', 'finish_node', 'fail_node', 'ignored_fail_node', 'ignored_doing_node', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['task_cmd', 'svr_pool', 'env_ver'], 'string', 'max' => 64],
            [['repo'], 'string', 'max' => 512],
            [['run_user'], 'string', 'max' => 32],
            [['task_id', 'svr_pool'], 'unique', 'targetAttribute' => ['task_id', 'svr_pool'], 'message' => 'The combination of Task ID and Svr Pool has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'task_cmd' => 'Task Cmd',
            'state' => 'State',
            'svr_pool' => 'Svr Pool',
            'svr_pool_id' => 'Svr Pool ID',
            'env_ver' => 'Env Ver',
            'repo' => 'Repo',
            'run_user' => 'Run User',
            'undo_node' => 'Undo Node',
            'doing_node' => 'Doing Node',
            'finish_node' => 'Finish Node',
            'fail_node' => 'Fail Node',
            'ignored_fail_node' => 'Ignored Fail Node',
            'ignored_doing_node' => 'Ignored Doing Node',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
