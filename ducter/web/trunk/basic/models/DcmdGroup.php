<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_group".
 *
 * @property integer $gid
 * @property string $gname
 * @property integer $gtype
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gname', 'gtype', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['gtype', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['gname'], 'string', 'max' => 64],
            [['comment'], 'string', 'max' => 512],
            [['gname'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gid' => 'Gid',
            'gname' => 'Gname',
            'gtype' => 'Gtype',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    public function convertGtype($gtype){
     if($gtype == 1) return "系统组";
     else return "业务组";
    }
}
