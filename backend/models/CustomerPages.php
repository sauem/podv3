<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer_pages".
 *
 * @property int $id
 * @property int|null $page_id
 * @property int|null $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property LandingPages $page
 * @property User $user
 */
class CustomerPages extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'user_id'], 'integer'],
            [['page_id', 'user_id'], 'required'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => LandingPages::className(), 'targetAttribute' => ['page_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserModel::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Page]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(LandingPages::className(), ['id' => 'page_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserModel::className(), ['id' => 'user_id']);
    }
}
