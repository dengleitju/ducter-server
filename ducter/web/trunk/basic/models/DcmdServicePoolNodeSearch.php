<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePoolNode;

/**
 * DcmdServicePoolNodeSearch represents the model behind the search form about `app\models\DcmdServicePoolNode`.
 */
class DcmdServicePoolNodeSearch extends DcmdServicePoolNode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'svr_pool_id', 'svr_id', 'nid', 'app_id', 'opr_uid'], 'integer'],
            [['ip', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdServicePoolNode::find()->orderBy('ip');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'svr_pool_id' => $this->svr_pool_id,
            'svr_id' => $this->svr_id,
            'nid' => $this->nid,
            'app_id' => $this->app_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
