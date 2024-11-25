<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempRwDiklat;

/**
 * SiasnTempRwDiklatSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempRwDiklat`.
 */
class SiasnTempRwDiklatSearch extends SiasnTempRwDiklat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bobot', 'jumlahJam', 'tahun', 'flag'], 'integer'],
            [['id', 'institusiPenyelenggara', 'jenisKompetensi', 'latihanStrukturalId', 'nomor', 'pnsOrangId', 'tanggal', 'tanggalSelesai', 'updated', 'by'], 'safe'],
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
        $query = SiasnTempRwDiklat::find();

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
            'bobot' => $this->bobot,
            'jumlahJam' => $this->jumlahJam,
            'tahun' => $this->tahun,
            'tanggal' => $this->tanggal,
            'tanggalSelesai' => $this->tanggalSelesai,
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'institusiPenyelenggara', $this->institusiPenyelenggara])
            ->andFilterWhere(['ilike', 'jenisKompetensi', $this->jenisKompetensi])
            ->andFilterWhere(['ilike', 'latihanStrukturalId', $this->latihanStrukturalId])
            ->andFilterWhere(['ilike', 'nomor', $this->nomor])
            ->andFilterWhere(['ilike', 'pnsOrangId', $this->pnsOrangId])
            ->andFilterWhere(['ilike', 'by', $this->by]);

        return $dataProvider;
    }
}
