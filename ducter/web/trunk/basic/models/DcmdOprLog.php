<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_opr_log".
 *
 * @property integer $id
 * @property string $log_table
 * @property integer $opr_type
 * @property string $sql_statement
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdOprLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_opr_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_table', 'opr_type', 'sql_statement', 'ctime', 'opr_uid'], 'required'],
            [['opr_type', 'opr_uid'], 'integer'],
            [['sql_statement'], 'string'],
            [['ctime'], 'safe'],
            [['log_table'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'log_table' => 'Log Table',
            'opr_type' => 'Opr Type',
            'sql_statement' => 'Sql Statement',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
