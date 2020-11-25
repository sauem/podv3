<?php

namespace backend\models;

use common\helper\Helper;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "warehouse_transaction".
 *
 * @property int $id
 * @property int|null $warehouse_id
 * @property int|null $quantity
 * @property string|null $note
 * @property string|null $product_sku
 * @property string|null $transaction_type
 * @property string|null $order_code
 * @property double|null $total_average
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
    const TRANSACTION_TYPE_REFUND = 3;
    const TRANSACTION_TYPE_BROKEN = 4;
    const TRANSACTION_TYPE_INVENTORY = 5;
    const TRANSACTION_PENDING_EXPORT = 6;

    const TRANSACTION_TYPE = [
        self::TRANSACTION_TYPE_IMPORT => 'Nhập kho',
        self::TRANSACTION_TYPE_EXPORT => 'Xuất kho',
        self::TRANSACTION_TYPE_REFUND => 'Hoàn',
        self::TRANSACTION_TYPE_BROKEN => 'Hỏng',
        self::TRANSACTION_TYPE_INVENTORY => 'Tồn kho', // tồn kho =  nhập + hoàn - xuất + hỏng
        self::TRANSACTION_PENDING_EXPORT => 'Chưa xuất hàng', // Chưa xuất hàng = tổng số lượng sản phẩm đã lên đơn chưa vận chuyển
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
            [['warehouse_id', 'transaction_type', 'quantity', 'status', 'created_at', 'updated_at'], 'integer'],
            [['warehouse_id', 'quantity', 'product_sku'], 'required'],
            [['total_average'], 'double'],
            [['note', 'order_code', 'product_sku'], 'string', 'max' => 255],
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
            'total_average' => 'Trung bình giá',
            'note' => 'Note',
            'product_sku' => 'Mã sản phẩm',
            'transaction_type' => 'Transaction Type',
            'order_code' => 'Order Code',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $warehouse_id
     * @param $product_sku
     * @param $quantity
     * @param $transaction_type
     * @return bool
     * @throws BadRequestHttpException
     */
    public static function doFirstImport($warehouse_id, $product_sku, $quantity)
    {
        $model = new WarehouseTransaction();
        if ($model->load([
                'warehouse_id' => $warehouse_id,
                'product_sku' => $product_sku,
                'quantity' => $quantity,
                'transaction_type' => self::TRANSACTION_TYPE_IMPORT
            ], '') && $model->save()) {
            return true;
        }
        throw new BadRequestHttpException(Helper::firstError($model));
    }
}
