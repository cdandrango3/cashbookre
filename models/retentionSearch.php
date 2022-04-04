<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Retention;

/**
 * retentionSearch represents the model behind the search form of `app\models\Retention`.
 */
class retentionSearch extends Retention
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_chart', 'id_charting', 'type'], 'integer'],
            [['percentage'], 'number'],
            [['codesri', 'slug'], 'safe'],
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
        $query = Retention::find();

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
            'id_chart' => $this->id_chart,
            'percentage' => $this->percentage,
            'id_charting' => $this->id_charting,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['ilike', 'codesri', $this->codesri])
            ->andFilterWhere(['ilike', 'slug', $this->slug]);

        return $dataProvider;
    }
}
