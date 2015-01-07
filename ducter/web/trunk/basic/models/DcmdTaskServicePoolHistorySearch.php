<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskServicePoolHistory;

/**
 * DcmdTaskServicePoolHistorySearch represents the model behind the search form about `app\models\DcmdTaskServicePoolHistory`.
 */
class DcmdTaskServicePoolHistorySearch extends DcmdTaskServicePoolHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'svr_pool_id', 'undo_node', 'doing_node', 'finish_node', 'fail_node', 'ignored_fail_node', 'ignored_doing_node', 'opr_uid'], 'integer'],
            [['task_cmd', 'svr_pool', 'env_ver', 'repo', 'run_user', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdTaskServicePoolHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'task_id' => $this->task_id,
            'svr_pool_id' => $this->svr_pool_id,
            'undo_node' => $this->undo_node,
            'doing_node' => $this->doing_node,
            'finish_node' => $this->finish_node,
            'fail_node' => $this->fail_node,
            'ignored_fail_node' => $this->ignored_fail_node,
            'ignored_doing_node' => $this->ignored_doing_node,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'task_cmd', $this->task_cmd])
            ->andFilterWhere(['like', 'svr_pool', $this->svr_pool])
            ->andFilterWhere(['like', 'env_ver', $this->env_ver])
            ->andFilterWhere(['like', 'repo', $this->repo])
            ->andFilterWhere(['like', 'run_user', $this->run_user]);

        return $dataProvider;
    }
}
