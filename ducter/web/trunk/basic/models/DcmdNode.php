<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dcmd_node".
 *
 * @property integer $nid
 * @property string $ip
 * @property integer $ngroup_id
 * @property string $host
 * @property string $sid
 * @property string $did
 * @property string $os_type
 * @property string $os_ver
 * @property string $bend_ip
 * @property string $public_ip
 * @property string $mach_room
 * @property string $rack
 * @property string $seat
 * @property string $online_time
 * @property string $server_brand
 * @property string $server_model
 * @property string $cpu
 * @property string $memory
 * @property string $disk
 * @property string $purchase_time
 * @property string $maintain_time
 * @property string $maintain_fac
 * @property string $comment
 * @property string $utime
 * @property string $ctime
 * @property integer $opr_uid
 */
class DcmdNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'ngroup_id', 'host', 'sid', 'did', 'os_type', 'os_ver', 'bend_ip', 'public_ip', 'mach_room', 'rack', 'seat', 'online_time', 'server_brand', 'server_model', 'cpu', 'memory', 'disk', 'purchase_time', 'maintain_time', 'maintain_fac', 'comment', 'utime', 'ctime', 'opr_uid'], 'required'],
            [['ngroup_id', 'opr_uid'], 'integer'],
            [['online_time', 'purchase_time', 'maintain_time', 'utime', 'ctime'], 'safe'],
            [['ip', 'bend_ip', 'public_ip'], 'string', 'max' => 16],
            [['host', 'sid', 'did', 'os_type', 'os_ver', 'mach_room', 'server_brand', 'maintain_fac'], 'string', 'max' => 128],
            [['rack', 'seat', 'server_model', 'cpu', 'memory'], 'string', 'max' => 32],
            [['disk'], 'string', 'max' => 64],
            [['comment'], 'string', 'max' => 512],
            [['ip','bend_ip','public_ip'], 'match', 'pattern'=>'/^(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])$/'],
            [['ip'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => 'Nid',
            'ip' => 'Ip',
            'ngroup_id' => 'Ngroup ID',
            'host' => 'Host',
            'sid' => 'Sid',
            'did' => 'Did',
            'os_type' => 'Os Type',
            'os_ver' => 'Os Ver',
            'bend_ip' => 'Bend Ip',
            'public_ip' => 'Public Ip',
            'mach_room' => 'Mach Room',
            'rack' => 'Rack',
            'seat' => 'Seat',
            'online_time' => 'Online Time',
            'server_brand' => 'Server Brand',
            'server_model' => 'Server Model',
            'cpu' => 'Cpu',
            'memory' => 'Memory',
            'disk' => 'Disk',
            'purchase_time' => 'Purchase Time',
            'maintain_time' => 'Maintain Time',
            'maintain_fac' => 'Maintain Fac',
            'comment' => 'Comment',
            'utime' => 'Utime',
            'ctime' => 'Ctime',
            'opr_uid' => 'Opr Uid',
        ];
    }
    /**
     *Get dcmd node group name
     */
    public function getNodeGname($ngid) {
      $query = DcmdNodeGroup::findOne($ngid);
      if ($query) return $query->ngroup_name;
      return "";
    }
}
