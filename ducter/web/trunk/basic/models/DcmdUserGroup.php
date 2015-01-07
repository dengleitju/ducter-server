<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_user_group".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $gid
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdUserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'gid', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['uid', 'gid', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['comment'], 'string', 'max' => 512],
            [['uid', 'gid'], 'unique', 'targetAttribute' => ['uid', 'gid'], 'message' => 'The combination of Uid and Gid has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'gid' => 'Gid',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
   public function getUsername($uid) 
   {
     $query = DcmdUser::findOne($uid);
     if($query) return $query['username'];
     else return "";
   }
   public function getDepartment($uid) 
   {
     $query = DcmdUser::findOne($uid);
     if(!$query) return "";
     $ret = DcmdDepartment::findOne($query['depart_id']);
     if($ret) return "";
     return $ret['depart_name'];
   }
}
