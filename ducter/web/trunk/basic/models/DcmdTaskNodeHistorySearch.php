<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskNodeHistory;

/**
 * DcmdTaskNodeHistorySearch represents the model behind the search form about `app\models\DcmdTaskNodeHistory`.
 */
class DcmdTaskNodeHistorySearch extends DcmdTaskNodeHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subtask_id', 'task_id', 'state', 'ignored', 'opr_uid'], 'integer'],
            [['task_cmd', 'svr_pool', 'svr_name', 'ip', 'start_time', 'finish_time', 'process', 'err_msg', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdTaskNodeHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'subtask_id' => $this->subtask_id,
            'task_id' => $this->task_id,
            'state' => $this->state,
            'ignored' => $this->ignored,
            'start_time' => $this->start_time,
            'finish_time' => $this->finish_time,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'task_cmd', $this->task_cmd])
            ->andFilterWhere(['like', 'svr_pool', $this->svr_pool])
            ->andFilterWhere(['like', 'svr_name', $this->svr_name])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'process', $this->process])
            ->andFilterWhere(['like', 'err_msg', $this->err_msg]);

        return $dataProvider;
    }
}
