<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempRwKursus;

/**
 * SiasnTempRwKursusSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempRwKursus`.
 */
class SiasnTempRwKursusSearch extends SiasnTempRwKursus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'instansiId', 'institusiPenyelenggara', 'jenisDiklatId', 'jenisKursus', 'jenisKursusSertipikat', 'lokasiId', 'namaKursus', 'nomorSertipikat', 'pnsOrangId', 'tanggalKursus', 'tanggalSelesaiKursus'], 'safe'],
            [['jumlahJam', 'tahunKursus'], 'integer'],
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
        $query = SiasnTempRwKursus::find();

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
            'jumlahJam' => $this->jumlahJam,
            'tahunKursus' => $this->tahunKursus,
            'tanggalKursus' => $this->tanggalKursus,
            'tanggalSelesaiKursus' => $this->tanggalSelesaiKursus,
        ]);

        $query->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'instansiId', $this->instansiId])
            ->andFilterWhere(['ilike', 'institusiPenyelenggara', $this->institusiPenyelenggara])
            ->andFilterWhere(['ilike', 'jenisDiklatId', $this->jenisDiklatId])
            ->andFilterWhere(['ilike', 'jenisKursus', $this->jenisKursus])
            ->andFilterWhere(['ilike', 'jenisKursusSertipikat', $this->jenisKursusSertipikat])
            ->andFilterWhere(['ilike', 'lokasiId', $this->lokasiId])
            ->andFilterWhere(['ilike', 'namaKursus', $this->namaKursus])
            ->andFilterWhere(['ilike', 'nomorSertipikat', $this->nomorSertipikat])
            ->andFilterWhere(['ilike', 'pnsOrangId', $this->pnsOrangId]);

        return $dataProvider;
    }
}
