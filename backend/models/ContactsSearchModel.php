<?php

namespace backend\models;

use common\helper\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContactsModel;
use yii\helpers\ArrayHelper;

/**
 * ContactsSearchModel represents the model behind the search form of `backend\models\ContactsModel`.
 */
class ContactsSearchModel extends ContactsModel
{
    /**
     * {@inheritdoc}
     */
    public $assign;
    public $user_id;

    public function rules()
    {
        return [
            [['assign', 'user_id'], 'safe'],
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

        // add conditions that should always apply here
        if (Helper::userRole(UserModel::_SALE)) {
            $status = ContactsAssignment::lastStatusAssignment();
            $query->innerJoin('contacts_assignment',
                'contacts_assignment.contact_phone=contacts.phone')
                ->where(['=', 'contacts_assignment.user_id', \Yii::$app->user->getId()])
                ->andWhere(['=', 'contacts_assignment.status', $status]);
        } else {
            if ($group) {
                $query->groupBy(['phone'])
                    ->with('assignment')
                    ->orderBy(['created_at' => SORT_ASC]);

                $isSearch = \Yii::$app->request->get('ContactsSearchModel');
                if ($isSearch) {
                    $assign =  ArrayHelper::getValue($isSearch, 'assign');
                    $users = ArrayHelper::getValue($isSearch, 'user_id');
                    if ($users) {
                        $query->innerJoin('contacts_assignment', 'contacts_assignment.contact_phone=contacts.phone'
                        )->where(['IN', 'contacts_assignment.user_id', $users]);
                    }
                    if($assign){
                       switch ($assign){
                           case "pending":
                               $assign = ContactsAssignment::find()->addSelect(['contact_phone'])->distinct()->asArray()->all();
                               $phones  = ArrayHelper::getColumn($assign,'contact_phone');

                               $query->leftJoin('contacts_assignment', 'contacts_assignment.contact_phone = contacts.phone')
                                   ->where(['NOT IN','contacts.phone',$phones]);
                               break;
                           case "approved":
                               $query->innerJoin('contacts_assignment', 'contacts_assignment.contact_phone = contacts.phone');
                               break;
                       }
                    }
                }

            }
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
            ->andFilterWhere(['=', 'phone', $this->name])
            ->orFilterWhere(['like', 'email', $this->name])
            ->andFilterWhere(['IN', 'contacts.status', $this->status]);


        return $dataProvider;
    }
}
