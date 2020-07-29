<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $name
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Orders[] $orders
 * @property PaymentInfo[] $paymentInfos
 */
class Payment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['description', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Mô tả',
            'name' => 'Phương thức',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(OrdersModel::className(), ['payment_method' => 'id']);
    }

    /**
     * Gets query for [[PaymentInfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInfos()
    {
        return $this->hasMany(PaymentInfo::className(), ['payment_id' => 'id']);
    }
}
