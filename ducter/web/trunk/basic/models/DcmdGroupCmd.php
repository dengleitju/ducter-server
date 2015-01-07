<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_group_cmd".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $opr_cmd_id
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdGroupCmd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_group_cmd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'opr_cmd_id', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['gid', 'opr_cmd_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['gid', 'opr_cmd_id'], 'unique', 'targetAttribute' => ['gid', 'opr_cmd_id'], 'message' => 'The combination of Gid and Opr Cmd ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
            'opr_cmd_id' => 'Opr Cmd ID',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
