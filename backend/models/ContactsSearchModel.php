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
    public function search($params, $group = true)
    {
        $query = ContactsModel::find()->orderBy(['contacts.status' => SORT_ASC]);
        if(!Helper::userRole(UserModel::_SALE) && $group){
            $query->groupBy(['phone'])->orderBy(['created_at'  => SORT_DESC])->with('assignment');
        }
        // add conditions that should always apply here
        if(Helper::userRole(UserModel::_SALE)){
            $query->innerJoin('contacts_assignment',
                'contacts_assignment.contact_phone=contacts.phone')
                ->where(['=','contacts_assignment.user_id', \Yii::$app->user->getId() ])
                ->andWhere(['=','contacts_assignment.status', ContactsAssignment::_PROCESSING]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'phone', $this->phone])
            ->orFilterWhere(['like', 'email', $this->name])
            ->andFilterWhere(['IN', 'contacts.status', $this->status]);


        return $dataProvider;
    }
}
