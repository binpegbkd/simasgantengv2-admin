<?php

namespace app\modules\sitampan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sitampan\models\PreskinPaguTpp;

/**
 * PreskinPaguTppSearch represents the model behind the search form of `app\modules\sitampan\models\PreskinPaguTpp`.
 */
class PreskinPaguTppSearch extends PreskinPaguTpp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pagu', 'flag'], 'integer'],
            [['kelas', 'ket', 'updated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = PreskinPaguTpp::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'pagu' => $this->pagu,
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'kelas', $this->kelas])
            ->andFilterWhere(['ilike', 'ket', $this->ket]);

        return $dataProvider;
    }
}
