<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_service_pool".
 *
 * @property integer $svr_pool_id
 * @property string $svr_pool
 * @property integer $svr_id
 * @property integer $app_id
 * @property string $repo
 * @property string $env_ver
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdServicePool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service_pool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_pool', 'svr_id', 'app_id', 'repo', 'env_ver', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['svr_id', 'app_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['svr_pool'], 'string', 'max' => 128],
            [['repo', 'comment'], 'string', 'max' => 512],
            [['env_ver'], 'string', 'max' => 64],
            [['svr_id', 'svr_pool'], 'unique', 'targetAttribute' => ['svr_id', 'svr_pool'], 'message' => 'The combination of Svr Pool and Svr ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_pool_id' => 'Svr Pool ID',
            'svr_pool' => 'Svr Pool',
            'svr_id' => 'Svr ID',
            'app_id' => 'App ID',
            'repo' => 'Repo',
            'env_ver' => 'Env Ver',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function getAppName($app_id) {
      $ret = DcmdApp::findOne($app_id);
      if ($ret) return $ret['app_name'];
      else return '';
    }
   public function getServiceName($svr_id) {
     $ret = DcmdService::findOne($svr_id);
     if($ret) return $ret['svr_name'];
     else return '';
   }
}
