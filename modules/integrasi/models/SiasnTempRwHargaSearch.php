<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempRwHarga;

/**
 * SiasnTempRwHargaSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempRwHarga`.
 */
class SiasnTempRwHargaSearch extends SiasnTempRwHarga
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hargaId', 'id', 'pnsOrangId', 'skDate', 'skNomor', 'updated', 'by'], 'safe'],
            [['tahun', 'flag'], 'integer'],
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
        $query = SiasnTempRwHarga::find();

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
            'skDate' => $this->skDate,
            'tahun' => $this->tahun,
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'hargaId', $this->hargaId])
            ->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'pnsOrangId', $this->pnsOrangId])
            ->andFilterWhere(['ilike', 'skNomor', $this->skNomor])
            ->andFilterWhere(['ilike', 'by', $this->by]);

        return $dataProvider;
    }
}
