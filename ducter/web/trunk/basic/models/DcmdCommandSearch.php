<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdCommand;

/**
 * DcmdCommandSearch represents the model behind the search form about `app\models\DcmdCommand`.
 */
class DcmdCommandSearch extends DcmdCommand
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cmd_id', 'task_id', 'subtask_id', 'svr_pool_id', 'cmd_type', 'state', 'opr_uid'], 'integer'],
            [['svr_pool', 'svr_name', 'ip', 'err_msg', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdCommand::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cmd_id' => $this->cmd_id,
            'task_id' => $this->task_id,
            'subtask_id' => $this->subtask_id,
            'svr_pool_id' => $this->svr_pool_id,
            'cmd_type' => $this->cmd_type,
            'state' => $this->state,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'svr_pool', $this->svr_pool])
            ->andFilterWhere(['like', 'svr_name', $this->svr_name])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'err_msg', $this->err_msg]);

        return $dataProvider;
    }
}
