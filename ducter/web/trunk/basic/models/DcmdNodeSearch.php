<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdNode;

/**
 * DcmdNodeSearch represents the model behind the search form about `app\models\DcmdNode`.
 */
class DcmdNodeSearch extends DcmdNode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nid', 'ngroup_id', 'opr_uid'], 'integer'],
            [['ip', 'host', 'sid', 'did', 'os_type', 'os_ver', 'bend_ip', 'public_ip', 'mach_room', 'rack', 'seat', 'online_time', 'server_brand', 'server_model', 'cpu', 'memory', 'disk', 'purchase_time', 'maintain_time', 'maintain_fac', 'comment', 'utime', 'ctime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DcmdNode::find()->orderBy('ip');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => '20',]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'nid' => $this->nid,
            'ngroup_id' => $this->ngroup_id,
            'online_time' => $this->online_time,
            'purchase_time' => $this->purchase_time,
            'maintain_time' => $this->maintain_time,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'sid', $this->sid])
            ->andFilterWhere(['like', 'did', $this->did])
            ->andFilterWhere(['like', 'os_type', $this->os_type])
            ->andFilterWhere(['like', 'os_ver', $this->os_ver])
            ->andFilterWhere(['like', 'bend_ip', $this->bend_ip])
            ->andFilterWhere(['like', 'public_ip', $this->public_ip])
            ->andFilterWhere(['like', 'mach_room', $this->mach_room])
            ->andFilterWhere(['like', 'rack', $this->rack])
            ->andFilterWhere(['like', 'seat', $this->seat])
            ->andFilterWhere(['like', 'server_brand', $this->server_brand])
            ->andFilterWhere(['like', 'server_model', $this->server_model])
            ->andFilterWhere(['like', 'cpu', $this->cpu])
            ->andFilterWhere(['like', 'memory', $this->memory])
            ->andFilterWhere(['like', 'disk', $this->disk])
            ->andFilterWhere(['like', 'maintain_fac', $this->maintain_fac])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
