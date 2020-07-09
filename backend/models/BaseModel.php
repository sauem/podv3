<?php


namespace backend\models;


use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }
        $this->updated_at = time();

        return parent::beforeSave($insert);
    }
}