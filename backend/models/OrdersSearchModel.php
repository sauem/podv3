<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OrdersModel;

/**
 * OrdersSearchModel represents the model behind the search form of `backend\models\OrdersModel`.
 */
class OrdersSearchModel extends OrdersModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'zipcode', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['customer_name', 'customer_phone', 'customer_email', 'address', 'city', 'district', 'country', 'order_note', 'status', 'status_note'], 'safe'],
            [['sale', 'sub_total', 'total'], 'number'],
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
        $query = OrdersModel::find();

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
            'zipcode' => $this->zipcode,
            'sale' => $this->sale,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone', $this->customer_phone])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'order_note', $this->order_note])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'status_note', $this->status_note]);

        return $dataProvider;
    }
}
