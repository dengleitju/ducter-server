<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_app".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property string $app_alias
 * @property integer $sa_gid
 * @property integer $svr_gid
 * @property integer $depart_id
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdApp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_name', 'app_alias', 'sa_gid', 'svr_gid', 'depart_id', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['sa_gid', 'svr_gid', 'depart_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['app_name', 'app_alias'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 512],
            [['app_name'], 'unique'],
            [['app_alias'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'app_name' => 'App Name',
            'app_alias' => 'App Alias',
            'sa_gid' => 'Sa Gid',
            'svr_gid' => 'Svr Gid',
            'depart_id' => 'Depart ID',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function userGroupName($gid) {
      $ret = DcmdGroup::findOne($gid);
      if($ret) return $ret['gname'];
      else return "";
    }
   public function department($depart_id) {
     $ret = DcmdDepartment::findOne($depart_id);
     if($ret) return $ret['depart_name'];
     else return "";
   }
}
