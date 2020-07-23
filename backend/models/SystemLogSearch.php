<?php


namespace backend\models;


use cakebake\actionlog\model\ActionLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SystemLogSearch extends ActionLog
{
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['user_remote', 'time', 'action', 'category', 'status', 'message'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ActionLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['time' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->orFilterWhere(['like', 'user_remote', $this->action])
            ->orFilterWhere(['like', 'category', $this->action])
            ->orFilterWhere(['like', 'message', $this->action]);
        return $dataProvider;
    }

}