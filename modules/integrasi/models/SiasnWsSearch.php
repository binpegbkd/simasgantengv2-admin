<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnWs;

/**
 * SiasnWsSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnWs`.
 */
class SiasnWsSearch extends SiasnWs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'flag'], 'integer'],
            [['method', 'path', 'param', 'name', 'updated'], 'safe'],
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
        $query = SiasnWs::find();

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
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'method', $this->method])
            ->andFilterWhere(['ilike', 'path', $this->path])
            ->andFilterWhere(['ilike', 'param', $this->param])
            ->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
