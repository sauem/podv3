<?php

namespace backend\models;

use common\helper\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContactsAssignment;

/**
 * ContactsAssignmentSearch represents the model behind the search form of `backend\models\ContactsAssignment`.
 */
class ContactsAssignmentSearch extends ContactsAssignment
{
    /**
     * {@inheritdoc}
     */
    public $callback;

    public function rules()
    {
        return [
            [['id', 'user_id', 'callback_time', 'created_at', 'updated_at'], 'integer'],
            [['contact_phone', 'status', 'callback'], 'safe'],
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
        $query = ContactsAssignment::find()->orderBy(['status' => SORT_DESC]);

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

        if ($this->callback == true) {
            $query->innerJoin("contacts", "contacts.phone=contacts_assignment.contact_phone")
                ->where(['contacts.status' => [ContactsModel::_CALLBACK,ContactsModel::_PENDING]])
                ->andWhere(['contacts_assignment.status' => [ContactsAssignment::_PENDING]])
                ->andWhere(['contacts_assignment.callback_time' => 1]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'callback_time' => $this->callback_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
