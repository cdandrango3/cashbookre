<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\retention;

/**
 * retentionSearch represents the model behind the search form about `app\models\retention`.
 */
class retentionSearch extends retention
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
        $query = retention::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_chart' => $this->id_chart,
            'percentage' => $this->percentage,
            'id_charting' => $this->id_charting,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'codesri', $this->codesri])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
