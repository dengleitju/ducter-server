<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdUser;

/**
 * DcmdUserSearch represents the model behind the search form about `app\models\DcmdUser`.
 */
class DcmdUserSearch extends DcmdUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sa', 'admin', 'depart_id', 'state', 'opr_uid'], 'integer'],
            [['username', 'passwd', 'tel', 'email', 'comment', 'utime', 'ctime'], 'safe'],
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
    public function search($params, $qstr=NULL)
    {
        if($qstr) $query = DcmdUser::find()->andWhere($qstr)->orderBy("username");
        else $query = DcmdUser::find()->orderBy("username");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pagesize' => 20],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'uid' => $this->uid,
            'sa' => $this->sa,
            'admin' => $this->admin,
            'depart_id' => $this->depart_id,
            'state' => $this->state,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'passwd', $this->passwd])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'comment', $this->comment]);
        return $dataProvider;
    }
}
