<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdDepartment;

/**
 * DcmdDepartmentSearch represents the model behind the search form about `app\models\DcmdDepartment`.
 */
class DcmdDepartmentSearch extends DcmdDepartment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['depart_id', 'opr_uid'], 'integer'],
            [['depart_name', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdDepartment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'depart_id' => $this->depart_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'depart_name', $this->depart_name])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
