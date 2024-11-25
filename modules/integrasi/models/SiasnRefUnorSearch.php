<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnRefUnor;

/**
 * SiasnRefUnorSearch represents the model behind the search form of `app\modules\siasn\models\SiasnRefUnor`.
 */
class SiasnRefUnorSearch extends SiasnRefUnor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'instansiId', 'diatasanId', 'namaUnor', 'namaJabatan', 'aktif', 'updated'], 'safe'],
            [['eselonId'], 'integer'],
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
        $query = SiasnRefUnor::find();

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
            'eselonId' => $this->eselonId,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'instansiId', $this->instansiId])
            ->andFilterWhere(['ilike', 'diatasanId', $this->diatasanId])
            ->andFilterWhere(['ilike', 'namaUnor', $this->namaUnor])
            ->andFilterWhere(['ilike', 'namaJabatan', $this->namaJabatan])
            ->andFilterWhere(['ilike', 'aktif', $this->aktif])
            ->andFilterWhere(['ilike', 'simpeg', $this->simpeg]);

        return $dataProvider;
    }
}
