<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "warehouse_storage".
 *
 * @property int $id
 * @property int|null $warehouse_id
 * @property int|null $product_id
 * @property int|null $quantity
 * @property int $created_at
 * @property int $updated_at
 */
class WarehouseStorage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warehouse_storage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'product_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'warehouse_id' => 'Warehouse ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
