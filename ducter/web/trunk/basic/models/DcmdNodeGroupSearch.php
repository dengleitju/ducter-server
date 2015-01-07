<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdNodeGroup;

/**
 * DcmdNodeGroupSearch represents the model behind the search form about `app\models\DcmdNodeGroup`.
 */
class DcmdNodeGroupSearch extends DcmdNodeGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ngroup_id', 'gid', 'opr_uid'], 'integer'],
            [['ngroup_name', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdNodeGroup::find()->orderBy("ngroup_name");
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ngroup_id' => $this->ngroup_id,
            'gid' => $this->gid,
        ]);
        $query->andFilterWhere(['like', 'ngroup_name', $this->ngroup_name]);
        return $dataProvider;
    }
}
