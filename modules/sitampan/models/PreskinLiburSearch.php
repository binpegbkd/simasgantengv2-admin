<?php

namespace app\modules\sitampan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sitampan\models\PreskinLibur;

/**
 * PreskinLiburSearch represents the model behind the search form of `app\modules\sitampan\models\PreskinLibur`.
 */
class PreskinLiburSearch extends PreskinLibur
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'detail', 'updated'], 'safe'],
            [['ket_libur'], 'integer'],
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
        $query = PreskinLibur::find();

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
            'tanggal' => $this->tanggal,
            'ket_libur' => $this->ket_libur,
            'updated' => $this->updated,
        ]);

        $query->andFilterWhere(['ilike', 'detail', $this->detail]);

        return $dataProvider;
    }
}
