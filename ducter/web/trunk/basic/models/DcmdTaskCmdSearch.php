<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdTaskCmd;

/**
 * DcmdTaskCmdSearch represents the model behind the search form about `app\models\DcmdTaskCmd`.
 */
class DcmdTaskCmdSearch extends DcmdTaskCmd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_cmd_id', 'timeout', 'opr_uid'], 'integer'],
            [['task_cmd', 'script_md5', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdTaskCmd::find()->orderBy('task_cmd');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'task_cmd_id' => $this->task_cmd_id,
            'timeout' => $this->timeout,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'task_cmd', $this->task_cmd])
            ->andFilterWhere(['like', 'script_md5', $this->script_md5])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
