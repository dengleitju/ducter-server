<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_opr_cmd".
 *
 * @property integer $opr_cmd_id
 * @property string $opr_cmd
 * @property string $ui_name
 * @property string $run_user
 * @property string $script_md5
 * @property integer $timeout
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdOprCmd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_opr_cmd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_cmd', 'ui_name', 'run_user', 'timeout', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['timeout', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['opr_cmd', 'run_user'], 'string', 'max' => 64],
            [['ui_name'], 'string', 'max' => 255],
            [['script_md5'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 512],
            [['opr_cmd'], 'unique'],
            [['ui_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'opr_cmd_id' => 'Opr Cmd ID',
            'opr_cmd' => 'Opr Cmd',
            'ui_name' => 'Ui Name',
            'run_user' => 'Run User',
            'script_md5' => 'Script Md5',
            'timeout' => 'Timeout',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
