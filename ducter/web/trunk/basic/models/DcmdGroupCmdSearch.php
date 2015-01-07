<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdGroupCmd;

/**
 * DcmdGroupCmdSearch represents the model behind the search form about `app\models\DcmdGroupCmd`.
 */
class DcmdGroupCmdSearch extends DcmdGroupCmd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gid', 'opr_cmd_id', 'opr_uid'], 'integer'],
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
        $query = DcmdGroupCmd::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'gid' => $this->gid,
            'opr_cmd_id' => $this->opr_cmd_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        return $dataProvider;
    }
}
