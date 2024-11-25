<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempRwHukdis;

/**
 * SiasnTempRwHukdisSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempRwHukdis`.
 */
class SiasnTempRwHukdisSearch extends SiasnTempRwHukdis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['akhirHukumanTanggal', 'alasanHukumanDisiplinId', 'golonganId', 'golonganLama', 'hukdisYangDiberhentikanId', 'hukumanTanggal', 'id', 'jenisHukumanId', 'jenisTingkatHukumanId', 'kedudukanHukumId', 'keterangan', 'nomorPp', 'pnsOrangId', 'skNomor', 'skPembatalanNomor', 'skPembatalanTanggal', 'skTanggal', 'updated', 'by'], 'safe'],
            [['masaBulan', 'masaTahun', 'flag'], 'integer'],
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
        $query = SiasnTempRwHukdis::find();

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
            'akhirHukumanTanggal' => $this->akhirHukumanTanggal,
            'hukumanTanggal' => $this->hukumanTanggal,
            'masaBulan' => $this->masaBulan,
            'masaTahun' => $this->masaTahun,
            'skPembatalanTanggal' => $this->skPembatalanTanggal,
            'skTanggal' => $this->skTanggal,
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'alasanHukumanDisiplinId', $this->alasanHukumanDisiplinId])
            ->andFilterWhere(['ilike', 'golonganId', $this->golonganId])
            ->andFilterWhere(['ilike', 'golonganLama', $this->golonganLama])
            ->andFilterWhere(['ilike', 'hukdisYangDiberhentikanId', $this->hukdisYangDiberhentikanId])
            ->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'jenisHukumanId', $this->jenisHukumanId])
            ->andFilterWhere(['ilike', 'jenisTingkatHukumanId', $this->jenisTingkatHukumanId])
            ->andFilterWhere(['ilike', 'kedudukanHukumId', $this->kedudukanHukumId])
            ->andFilterWhere(['ilike', 'keterangan', $this->keterangan])
            ->andFilterWhere(['ilike', 'nomorPp', $this->nomorPp])
            ->andFilterWhere(['ilike', 'pnsOrangId', $this->pnsOrangId])
            ->andFilterWhere(['ilike', 'skNomor', $this->skNomor])
            ->andFilterWhere(['ilike', 'skPembatalanNomor', $this->skPembatalanNomor])
            ->andFilterWhere(['ilike', 'by', $this->by]);

        return $dataProvider;
    }
}
