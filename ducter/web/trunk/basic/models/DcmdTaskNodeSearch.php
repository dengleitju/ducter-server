<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskNode;

/**
 * DcmdTaskNodeSearch represents the model behind the search form about `app\models\DcmdTaskNode`.
 */
class DcmdTaskNodeSearch extends DcmdTaskNode
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
    public function search($params, $page_size=5)
    {
        $query = DcmdTaskNode::find()->orderBy('utime desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => $page_size],
        ]);

        if (!($this->load($params) && $this->validate())) {
           return $dataProvider;
        }
        if(array_key_exists('task_id', $params)) $query->andFilterWhere(['task_id' => $params['task_id'],]);
        if(array_key_exists('state', $params)) $query->andFilterWhere(['state' => $params['state'],]);        

        if(array_key_exists('svr_pool', $params)) {
          if($params['svr_pool'] != "all") $query->andFilterWhere(['svr_pool' => $params['svr_pool']]);
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
            ->andFilterWhere(['=', 'svr_pool', $this->svr_pool])
            ->andFilterWhere(['like', 'svr_name', $this->svr_name])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'process', $this->process])
            ->andFilterWhere(['like', 'err_msg', $this->err_msg]);
        
        return $dataProvider;
    }



}
