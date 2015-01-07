<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdServicePool;

/**
 * DcmdServicePoolSearch represents the model behind the search form about `app\models\DcmdServicePool`.
 */
class DcmdServicePoolSearch extends DcmdServicePool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_pool_id', 'svr_id', 'app_id', 'opr_uid'], 'integer'],
            [['svr_pool', 'repo', 'env_ver', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdServicePool::find()->orderBy('svr_pool');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'svr_pool_id' => $this->svr_pool_id,
            'svr_id' => $this->svr_id,
            'app_id' => $this->app_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'svr_pool', $this->svr_pool])
            ->andFilterWhere(['like', 'repo', $this->repo])
            ->andFilterWhere(['like', 'env_ver', $this->env_ver])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
