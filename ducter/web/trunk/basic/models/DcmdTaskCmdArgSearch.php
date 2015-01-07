<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskCmdArg;

/**
 * DcmdTaskCmdArgSearch represents the model behind the search form about `app\models\DcmdTaskCmdArg`.
 */
class DcmdTaskCmdArgSearch extends DcmdTaskCmdArg
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_cmd_id', 'optional', 'arg_type', 'opr_uid'], 'integer'],
            [['task_cmd', 'arg_name', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdTaskCmdArg::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'task_cmd_id' => $this->task_cmd_id,
            'optional' => $this->optional,
            'arg_type' => $this->arg_type,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'task_cmd', $this->task_cmd])
            ->andFilterWhere(['like', 'arg_name', $this->arg_name]);

        return $dataProvider;
    }
}
