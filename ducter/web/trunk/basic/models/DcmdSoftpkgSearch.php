<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DcmdSoftpkg;

/**
 * DcmdSoftpkgSearch represents the model behind the search form about `app\models\DcmdSoftpkg`.
 */
class DcmdSoftpkgSearch extends DcmdSoftpkg
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'svr_id', 'opr_uid'], 'integer'],
            [['version', 'repo_file', 'upload_file', 'utime', 'ctime'], 'safe'],
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
        $query = DcmdSoftpkg::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'svr_id' => $this->svr_id,
            'utime' => $this->utime,
            'ctime' => $this->ctime,
            'opr_uid' => $this->opr_uid,
        ]);

        $query->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'repo_file', $this->repo_file])
            ->andFilterWhere(['like', 'upload_file', $this->upload_file]);

        return $dataProvider;
    }
}
