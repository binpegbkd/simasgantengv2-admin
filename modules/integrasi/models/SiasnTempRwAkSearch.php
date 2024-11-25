<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempRwAk;

/**
 * SiasnTempRwAkSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempRwAk`.
 */
class SiasnTempRwAkSearch extends SiasnTempRwAk
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bulanMulaiPenailan', 'bulanSelesaiPenailan', 'isAngkaKreditPertama', 'isIntegrasi', 'isKonversi', 'tahunMulaiPenailan', 'tahunSelesaiPenailan', 'flag'], 'integer'],
            [['id', 'nomorSk', 'pnsId', 'rwJabatanId', 'tanggalSk', 'updated', 'by'], 'safe'],
            [['kreditBaruTotal', 'kreditPenunjangBaru', 'kreditUtamaBaru'], 'number'],
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
        $query = SiasnTempRwAk::find();

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
            'bulanMulaiPenailan' => $this->bulanMulaiPenailan,
            'bulanSelesaiPenailan' => $this->bulanSelesaiPenailan,
            'isAngkaKreditPertama' => $this->isAngkaKreditPertama,
            'isIntegrasi' => $this->isIntegrasi,
            'isKonversi' => $this->isKonversi,
            'kreditBaruTotal' => $this->kreditBaruTotal,
            'kreditPenunjangBaru' => $this->kreditPenunjangBaru,
            'kreditUtamaBaru' => $this->kreditUtamaBaru,
            'tahunMulaiPenailan' => $this->tahunMulaiPenailan,
            'tahunSelesaiPenailan' => $this->tahunSelesaiPenailan,
            'tanggalSk' => $this->tanggalSk,
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'nomorSk', $this->nomorSk])
            ->andFilterWhere(['ilike', 'pnsId', $this->pnsId])
            ->andFilterWhere(['ilike', 'rwJabatanId', $this->rwJabatanId])
            ->andFilterWhere(['ilike', 'by', $this->by]);

        return $dataProvider;
    }
}
