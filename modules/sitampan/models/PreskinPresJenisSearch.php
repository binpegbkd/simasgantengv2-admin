<?php

namespace app\modules\sitampan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sitampan\models\PreskinPresJenis;

/**
 * PreskinPresJenisSearch represents the model behind the search form of `app\modules\sitampan\models\PreskinPresJenis`.
 */
class PreskinPresJenisSearch extends PreskinPresJenis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kd_presensi', 'keterangan', 'updated'], 'safe'],
            [['selisih_waktu'], 'integer'],
            [['persen_pot'], 'number'],
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
        $query = PreskinPresJenis::find();

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
            'selisih_waktu' => $this->selisih_waktu,
            'persen_pot' => $this->persen_pot,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'kd_presensi', $this->kd_presensi])
            ->andFilterWhere(['ilike', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
