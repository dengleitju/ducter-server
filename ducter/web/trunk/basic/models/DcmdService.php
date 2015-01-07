<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_service".
 *
 * @property integer $svr_id
 * @property string $svr_name
 * @property string $svr_alias
 * @property string $svr_path
 * @property string $run_user
 * @property integer $app_id
 * @property integer $owner
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_name', 'svr_alias', 'svr_path', 'run_user', 'app_id', 'owner', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['app_id', 'owner', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['svr_name', 'svr_alias', 'svr_path'], 'string', 'max' => 128],
            [['run_user'], 'string', 'max' => 16],
            [['comment'], 'string', 'max' => 512],
            [['app_id', 'svr_name'], 'unique', 'targetAttribute' => ['app_id', 'svr_name'], 'message' => 'The combination of Svr Name and App ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'svr_id' => 'Svr ID',
            'svr_name' => 'Svr Name',
            'svr_alias' => 'Svr Alias',
            'svr_path' => 'Svr Path',
            'run_user' => 'Run User',
            'app_id' => 'App ID',
            'owner' => 'Owner',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
   public function getAppName($app_id) {
     $ret = DcmdApp::findOne($app_id);
     if ($ret) return $ret['app_name'];
     return "";
   }
  public function getAppAlias($app_id) {
     $ret = DcmdApp::findOne($app_id);
     if ($ret) return $ret['app_alias'];
     else return "";
  }

}
