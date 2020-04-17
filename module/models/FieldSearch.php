<?php

namespace abcms\structure\module\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use abcms\structure\models\Field;

/**
 * FieldSearch represents the model behind the search form about `abcms\structure\models\Field`.
 */
class FieldSearch extends Field
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'structureId', 'ordering', 'isRequired', 'isTranslatable'], 'integer'],
            [['name', 'type', 'label'], 'safe'],
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
    public function search($params, $structureId = null)
    {
        $query = Field::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'structureId' => $structureId,
            'ordering' => $this->ordering,
            'isRequired' => $this->isRequired,
            'isTranslatable' => $this->isTranslatable,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
