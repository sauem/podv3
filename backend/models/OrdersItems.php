<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders_items".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $product_id
 * @property int|null $contact_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $qty
 * @property double $price
 *
 * @property Contacts $contact
 * @property Orders $order
 * @property Products $product
 */
class OrdersItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'contact_id','qty'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['price'], 'number'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContactsModel::className(), 'targetAttribute' => ['contact_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdersModel::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsModel::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'qty' => 'Số lượng',
            'price' => 'Giá sản phẩm',
            'product_id' => 'Product ID',
            'contact_id' => 'Contact ID',
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

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrdersModel::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductsModel::className(), ['id' => 'product_id']);
    }
}
