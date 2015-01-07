<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdOprCmdRepeatExec;

/**
 * DcmdOprCmdRepeatExecSearch represents the model behind the search form about `app\models\DcmdOprCmdRepeatExec`.
 */
class DcmdOprCmdRepeatExecSearch extends DcmdOprCmdRepeatExec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['repeat_cmd_id', 'timeout', 'repeat', 'cache_time', 'ip_mutable', 'arg_mutable', 'opr_uid'], 'integer'],
            [['repeat_cmd_name', 'opr_cmd', 'run_user', 'ip', 'arg', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdOprCmdRepeatExec::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'repeat_cmd_id' => $this->repeat_cmd_id,
            'timeout' => $this->timeout,
            'repeat' => $this->repeat,
            'cache_time' => $this->cache_time,
            'ip_mutable' => $this->ip_mutable,
            'arg_mutable' => $this->arg_mutable,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'repeat_cmd_name', $this->repeat_cmd_name])
            ->andFilterWhere(['like', 'opr_cmd', $this->opr_cmd])
            ->andFilterWhere(['like', 'run_user', $this->run_user])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'arg', $this->arg]);

        return $dataProvider;
    }
}
