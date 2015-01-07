<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_center".
 *
 * @property string $host
 * @property integer $master
 * @property string $update_time
 */
class DcmdCenter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_center';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['host', 'master', 'update_time'], 'required'],
            [['master'], 'integer'],
            [['update_time'], 'safe'],
            [['host'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'host' => 'Host',
            'master' => 'Master',
            'update_time' => 'Update Time',
        ];
    }
}
