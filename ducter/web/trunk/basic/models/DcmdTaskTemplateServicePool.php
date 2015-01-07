<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_task_template_service_pool".
 *
 * @property integer $id
 * @property integer $task_tmpt_id
 * @property integer $svr_pool_id
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdTaskTemplateServicePool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_task_template_service_pool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_tmpt_id', 'svr_pool_id', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['task_tmpt_id', 'svr_pool_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
            [['task_tmpt_id', 'svr_pool_id'], 'unique', 'targetAttribute' => ['task_tmpt_id', 'svr_pool_id'], 'message' => 'The combination of Task Tmpt ID and Svr Pool ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_tmpt_id' => 'Task Tmpt ID',
            'svr_pool_id' => 'Svr Pool ID',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }

   public function getServicePool($svr_pool_id) {
     $query = DcmdServicePool::findOne($svr_pool_id);
     if($query) return $query['svr_pool'];
     return "";
   }
}
