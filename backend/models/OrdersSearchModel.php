<?php

namespace backend\models;

use common\helper\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OrdersModel;
use yii\helpers\ArrayHelper;
use function GuzzleHttp\Psr7\str;

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
            [['id', 'zipcode'], 'integer'],
            [['customer_name','user_id', 'customer_phone', 'customer_email', 'address', 'city', 'district', 'country', 'order_note', 'status', 'status_note', 'created_at', 'updated_at'], 'safe'],
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
        $query = OrdersModel::find()->orderBy(['created_at' => SORT_DESC])->with('items')->with('contacts');

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

        $range = ArrayHelper::getValue($params,'OrdersSearchModel.created_at');
        $range = explode(" - ",$range);
        $range = array_map(function ($item){
            return trim($item);
        },$range);
        $start = null;
        $end = null;
        if(!empty($params['created_at'])){
            $start = strtotime(trim($range[0]));
            $end = strtotime(trim($range[1]));
        }


        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->orFilterWhere(['like', 'customer_phone', $this->customer_name])
            ->orFilterWhere(['like', 'customer_email', $this->customer_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'order_note', $this->order_note])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'status_note', $this->status_note])
            ->andFilterWhere(['IN', 'user_id', $this->user_id]);
        if($start && $end){
            $query->andFilterWhere(['between','created_at',$start,$end]);
        }
        return $dataProvider;
    }
}
