<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "warehouse_transaction".
 *
 * @property int $id
 * @property int|null $warehouse_id
 * @property int|null $quantity
 * @property string|null $note
 * @property int|null $product_id
 * @property string|null $transaction_type
 * @property string|null $order_code
 * @property int|null $status
 * @property int $created_at
 * @property int $updated_at
 */
class WarehouseTransaction extends BaseModel
{
    /**
     * {@inheritdoc}
     */

    const TRANSACTION_TYPE_IMPORT = 1;
    const TRANSACTION_TYPE_EXPORT = 2;

    const TRANSACTION_TYPE = [
        self::TRANSACTION_TYPE_IMPORT => 'Nhập kho',
        self::TRANSACTION_TYPE_EXPORT => 'Xuất kho',
    ];

    public static function tableName()
    {
        return 'warehouse_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'quantity', 'product_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['warehouse_id', 'quantity', 'product_id'], 'required'],
            [['note', 'transaction_type', 'order_code'], 'string', 'max' => 255],
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
            'quantity' => 'Quantity',
            'note' => 'Note',
            'product_id' => 'Product ID',
            'transaction_type' => 'Transaction Type',
            'order_code' => 'Order Code',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
