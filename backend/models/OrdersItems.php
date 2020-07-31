<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders_items".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $product_id

 * @property int $created_at
 * @property int $updated_at
 * @property int $qty
 * @property double $price
 *

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
            [['order_id','qty'], 'integer'],
            [['order_id', 'product_sku','price'], 'required'],
            [['price'], 'number'],
            [['product_sku'], 'string']
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
            'product_sku' => 'Product SKU',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
        return $this->hasOne(ProductsModel::className(), ['sku' => 'product_sku'])->with('category');
    }
}
