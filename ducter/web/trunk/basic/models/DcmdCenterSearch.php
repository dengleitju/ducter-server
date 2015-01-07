<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdCenter;

/**
 * DcmdCenterSearch represents the model behind the search form about `app\models\DcmdCenter`.
 */
class DcmdCenterSearch extends DcmdCenter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['host', 'update_time'], 'safe'],
            [['master'], 'integer'],
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
        $query = DcmdCenter::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'master' => $this->master,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'host', $this->host]);

        return $dataProvider;
    }
}
