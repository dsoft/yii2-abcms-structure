<?php

namespace abcms\structure\module\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use abcms\structure\models\Structure;

/**
 * StructureSearch represents the model behind the search form about `abcms\structure\models\Structure`.
 */
class StructureSearch extends Structure
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'modelId', 'pk'], 'integer'],
            [['name'], 'safe'],
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
        $query = Structure::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
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
            'modelId' => $this->modelId,
            'pk' => $this->pk,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
