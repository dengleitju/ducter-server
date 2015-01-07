<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdOprLog;

/**
 * DcmdOprLogSearch represents the model behind the search form about `app\models\DcmdOprLog`.
 */
class DcmdOprLogSearch extends DcmdOprLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'opr_type', 'opr_uid'], 'integer'],
            [['log_table', 'sql_statement', 'ctime'], 'safe'],
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
        $query = DcmdOprLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'opr_type' => $this->opr_type,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'log_table', $this->log_table])
            ->andFilterWhere(['like', 'sql_statement', $this->sql_statement]);

        return $dataProvider;
    }
}
