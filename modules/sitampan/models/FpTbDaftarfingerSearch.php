<?php

namespace app\modules\sitampan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sitampan\models\FpTbDaftarfinger;

/**
 * FpTbDaftarfingerSearch represents the model behind the search form of `app\modules\sitampan\models\FpTbDaftarfinger`.
 */
class FpTbDaftarfingerSearch extends FpTbDaftarfinger
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nip', 'password', 'nama', 'waktu', 'device'], 'safe'],
            [['kodealamat'], 'integer'],
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
        $query = FpTbDaftarfinger::find();

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
            'kodealamat' => $this->kodealamat,
            'waktu' => $this->waktu,
        ]);

        $query->andFilterWhere(['ilike', 'nip', $this->nip])
            ->andFilterWhere(['ilike', 'password', $this->password])
            ->andFilterWhere(['ilike', 'nama', $this->nama])
            ->andFilterWhere(['ilike', 'device', $this->device]);

        return $dataProvider;
    }
}
