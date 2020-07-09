<?php

namespace backend\models;

use common\helper\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContactsModel;

/**
 * ContactsSearchModel represents the model behind the search form of `backend\models\ContactsModel`.
 */
class ContactsSearchModel extends ContactsModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'zipcode', 'created_at', 'updated_at'], 'integer'],
            [['name', 'phone', 'email', 'address', 'option', 'ip', 'note', 'link', 'short_link', 'utm_source', 'utm_medium', 'utm_content', 'utm_term', 'utm_campaign', 'host', 'hashkey', 'status'], 'safe'],
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
        $query = ContactsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
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
            'zipcode' => $this->zipcode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'option', $this->option])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'short_link', $this->short_link])
            ->andFilterWhere(['like', 'utm_source', $this->utm_source])
            ->andFilterWhere(['like', 'utm_medium', $this->utm_medium])
            ->andFilterWhere(['like', 'utm_content', $this->utm_content])
            ->andFilterWhere(['like', 'utm_term', $this->utm_term])
            ->andFilterWhere(['like', 'utm_campaign', $this->utm_campaign])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'hashkey', $this->hashkey])
            ->andFilterWhere(['IN', 'status', $this->status]);

        return $dataProvider;
    }
}
