<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_node_group".
 *
 * @property integer $ngroup_id
 * @property string $ngroup_name
 * @property integer $gid
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdNodeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_node_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ngroup_name', 'gid', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['gid', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['ngroup_name'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 512],
            [['ngroup_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ngroup_id' => 'Ngroup ID',
            'ngroup_name' => 'Ngroup Name',
            'gid' => 'Gid',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    
    public function getGname($gid) {
      $query = DcmdGroup::findOne($gid);
      if ($query) return $query->gname;
      return "";
    }
}
