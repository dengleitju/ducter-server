<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdService;

/**
 * DcmdServiceSearch represents the model behind the search form about `app\models\DcmdService`.
 */
class DcmdServiceSearch extends DcmdService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svr_id', 'app_id', 'owner', 'opr_uid'], 'integer'],
            [['svr_name', 'svr_alias', 'svr_path', 'run_user', 'comment', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdService::find()->orderBy('svr_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'svr_id' => $this->svr_id,
            'app_id' => $this->app_id,
            'owner' => $this->owner,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'svr_name', $this->svr_name])
            ->andFilterWhere(['like', 'svr_alias', $this->svr_alias])
            ->andFilterWhere(['like', 'svr_path', $this->svr_path])
            ->andFilterWhere(['like', 'run_user', $this->run_user])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
