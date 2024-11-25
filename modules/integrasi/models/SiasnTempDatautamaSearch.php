<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempDatautama;

/**
 * SiasnTempDatautamaSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempDatautama`.
 */
class SiasnTempDatautamaSearch extends SiasnTempDatautama
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agama_id', 'alamat', 'email', 'email_gov', 'kabupaten_id', 'karis_karsu', 'kelas_jabatan', 'kpkn_id', 'lokasi_kerja_id', 'nomor_bpjs', 'nomor_hp', 'nomor_telpon', 'npwp_nomor', 'npwp_tanggal', 'pns_orang_id', 'tanggal_taspen', 'tapera_nomor', 'taspen_nomor', 'updated', 'by'], 'safe'],
            [['flag'], 'integer'],
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
        $query = SiasnTempDatautama::find();

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
            'npwp_tanggal' => $this->npwp_tanggal,
            'tanggal_taspen' => $this->tanggal_taspen,
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'agama_id', $this->agama_id])
            ->andFilterWhere(['ilike', 'alamat', $this->alamat])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'email_gov', $this->email_gov])
            ->andFilterWhere(['ilike', 'kabupaten_id', $this->kabupaten_id])
            ->andFilterWhere(['ilike', 'karis_karsu', $this->karis_karsu])
            ->andFilterWhere(['ilike', 'kelas_jabatan', $this->kelas_jabatan])
            ->andFilterWhere(['ilike', 'kpkn_id', $this->kpkn_id])
            ->andFilterWhere(['ilike', 'lokasi_kerja_id', $this->lokasi_kerja_id])
            ->andFilterWhere(['ilike', 'nomor_bpjs', $this->nomor_bpjs])
            ->andFilterWhere(['ilike', 'nomor_hp', $this->nomor_hp])
            ->andFilterWhere(['ilike', 'nomor_telpon', $this->nomor_telpon])
            ->andFilterWhere(['ilike', 'npwp_nomor', $this->npwp_nomor])
            ->andFilterWhere(['ilike', 'pns_orang_id', $this->pns_orang_id])
            ->andFilterWhere(['ilike', 'tapera_nomor', $this->tapera_nomor])
            ->andFilterWhere(['ilike', 'taspen_nomor', $this->taspen_nomor])
            ->andFilterWhere(['ilike', 'by', $this->by]);

        return $dataProvider;
    }
}
