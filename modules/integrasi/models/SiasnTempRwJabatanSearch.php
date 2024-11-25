<?php

namespace app\modules\integrasi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\integrasi\models\SiasnTempRwJabatan;

/**
 * SiasnTempRwJabatanSearch represents the model behind the search form of `app\modules\integrasi\models\SiasnTempRwJabatan`.
 */
class SiasnTempRwJabatanSearch extends SiasnTempRwJabatan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eselonId', 'id', 'instansiId', 'jabatanFungsionalId', 'jabatanFungsionalUmumId', 'jenisJabatan', 'jenisMutasiId', 'jenisPenugasanId', 'nomorSk', 'pnsId', 'satuanKerjaId', 'subJabatanId', 'tanggalSk', 'tmtJabatan', 'tmtMutasi', 'tmtPelantikan', 'unorId', 'updated', 'by'], 'safe'],
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
        $query = SiasnTempRwJabatan::find();

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
            'flag' => $this->flag,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'eselonId', $this->eselonId])
            ->andFilterWhere(['ilike', 'id', $this->id])
            ->andFilterWhere(['ilike', 'instansiId', $this->instansiId])
            ->andFilterWhere(['ilike', 'jabatanFungsionalId', $this->jabatanFungsionalId])
            ->andFilterWhere(['ilike', 'jabatanFungsionalUmumId', $this->jabatanFungsionalUmumId])
            ->andFilterWhere(['ilike', 'jenisJabatan', $this->jenisJabatan])
            ->andFilterWhere(['ilike', 'jenisMutasiId', $this->jenisMutasiId])
            ->andFilterWhere(['ilike', 'jenisPenugasanId', $this->jenisPenugasanId])
            ->andFilterWhere(['ilike', 'nomorSk', $this->nomorSk])
            ->andFilterWhere(['ilike', 'pnsId', $this->pnsId])
            ->andFilterWhere(['ilike', 'satuanKerjaId', $this->satuanKerjaId])
            ->andFilterWhere(['ilike', 'subJabatanId', $this->subJabatanId])
            ->andFilterWhere(['ilike', 'tanggalSk', $this->tanggalSk])
            ->andFilterWhere(['ilike', 'tmtJabatan', $this->tmtJabatan])
            ->andFilterWhere(['ilike', 'tmtMutasi', $this->tmtMutasi])
            ->andFilterWhere(['ilike', 'tmtPelantikan', $this->tmtPelantikan])
            ->andFilterWhere(['ilike', 'unorId', $this->unorId])
            ->andFilterWhere(['ilike', 'by', $this->by]);

        return $dataProvider;
    }
}
