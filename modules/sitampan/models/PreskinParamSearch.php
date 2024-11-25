<?php

namespace app\modules\sitampan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sitampan\models\PreskinParam;

/**
 * PreskinParamSearch represents the model behind the search form of `app\modules\sitampan\models\PreskinParam`.
 */
class PreskinParamSearch extends PreskinParam
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'menit_pres', 'menit_kin', 'batas_pres', 'batas_kin1', 'batas_kin2', 'batas_kin_nilai'], 'integer'],
            [['updated'], 'safe'],
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
        $query = PreskinParam::find();

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
            'id' => $this->id,
            'menit_pres' => $this->menit_pres,
            'menit_kin' => $this->menit_kin,
            'batas_pres' => $this->batas_pres,
            'batas_kin1' => $this->batas_kin1,
            'batas_kin2' => $this->batas_kin2,
            'batas_kin_nilai' => $this->batas_kin_nilai,
            'updated' => $this->updated,
        ]);

        return $dataProvider;
    }
}
