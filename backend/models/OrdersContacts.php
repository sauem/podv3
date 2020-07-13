<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders_contacts".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $contact_id
 * @property int|null $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Contacts $contact
 */
class OrdersContacts extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'contact_id', 'user_id'], 'integer'],
            [['order_id', 'contact_id','user_id'], 'required'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContactsModel::className(), 'targetAttribute' => ['contact_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'contact_id' => 'Contact ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Contact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(ContactsModel::className(), ['id' => 'contact_id']);
    }
}
