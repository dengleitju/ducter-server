<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_softpkg".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $svr_id
 * @property string $version
 * @property string $repo_file
 * @property string $upload_file
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdSoftpkg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_softpkg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'svr_id', 'version', 'repo_file', 'upload_file', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['app_id', 'svr_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['version'], 'string', 'max' => 64],
            [['repo_file', 'upload_file'], 'string', 'max' => 256],
            [['repo_file'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'svr_id' => 'Svr ID',
            'version' => 'Version',
            'repo_file' => 'Repo File',
            'upload_file' => 'Upload File',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
}
