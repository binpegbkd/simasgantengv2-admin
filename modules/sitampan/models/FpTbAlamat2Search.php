<?php

namespace app\modules\sitampan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sitampan\models\FpTbAlamat2;

/**
 * FpTbAlamat2Search represents the model behind the search form of `app\modules\sitampan\models\FpTbAlamat2`.
 */
class FpTbAlamat2Search extends FpTbAlamat2
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kodealamat'], 'integer'],
            [['alamat', 'latitude', 'longitude', 'tablokb'], 'safe'],
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
        $query = FpTbAlamat2::find();

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
            'tablokb' => $this->tablokb,
        ]);

        $query->andFilterWhere(['ilike', 'alamat', $this->alamat])
            ->andFilterWhere(['ilike', 'latitude', $this->latitude])
            ->andFilterWhere(['ilike', 'longitude', $this->longitude]);

        return $dataProvider;
    }
}
