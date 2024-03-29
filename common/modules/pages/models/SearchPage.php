<?php

namespace common\modules\pages\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\pages\models\Page;

/**
 * SearchPage represents the model behind the search form of `common\modules\pages\models\Page`.
 */
class SearchPage extends Page
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_published'], 'integer'],
            [['title', 'seo_title', 'seo_description', 'seo_keywords', 'slug', 'html', 'insert_stamp', 'update_stamp'], 'safe'],
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
        $query = Page::find();

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
            'is_published' => $this->is_published,
            'insert_stamp' => $this->insert_stamp,
            'update_stamp' => $this->update_stamp,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'seo_description', $this->seo_description])
            ->andFilterWhere(['like', 'seo_keywords', $this->seo_keywords])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'html', $this->html]);

        return $dataProvider;
    }
}
