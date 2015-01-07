<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_opr_cmd_arg".
 *
 * @property integer $id
 * @property integer $opr_cmd_id
 * @property string $arg_name
 * @property integer $optional
 * @property integer $arg_type
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdOprCmdArg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_opr_cmd_arg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opr_cmd_id', 'arg_name', 'optional', 'arg_type', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['opr_cmd_id', 'optional', 'arg_type', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['arg_name'], 'string', 'max' => 32],
            [['opr_cmd_id', 'arg_name'], 'unique', 'targetAttribute' => ['opr_cmd_id', 'arg_name'], 'message' => 'The combination of Opr Cmd ID and Arg Name has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'opr_cmd_id' => 'Opr Cmd ID',
            'arg_name' => 'Arg Name',
            'optional' => 'Optional',
            'arg_type' => 'Arg Type',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
