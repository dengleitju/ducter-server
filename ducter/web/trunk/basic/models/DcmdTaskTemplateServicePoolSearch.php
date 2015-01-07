<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskTemplateServicePool;

/**
 * DcmdTaskTemplateServicePoolSearch represents the model behind the search form about `app\models\DcmdTaskTemplateServicePool`.
 */
class DcmdTaskTemplateServicePoolSearch extends DcmdTaskTemplateServicePool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_tmpt_id', 'svr_pool_id', 'opr_uid'], 'integer'],
            [['utime', 'ctime'], 'safe'],
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
        $query = DcmdTaskTemplateServicePool::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'task_tmpt_id' => $this->task_tmpt_id,
            'svr_pool_id' => $this->svr_pool_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        return $dataProvider;
    }
}
