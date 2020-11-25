<?php

namespace backend\models;

use common\helper\Helper;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "warehouse_storage".
 *
 * @property int $id
 * @property int|null $warehouse_id
 * @property string|null $product_sku
 * @property int|null $quantity
 * @property string|null $po_code
 * @property double|null $unit_price
 * @property int $created_at
 * @property int $updated_at
 */
class WarehouseStorage extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const STATUS_CANCEL = 0;
    const STATUS_CONFIRM = 1;

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
            [['warehouse_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['unit_price'], 'double'],
            [['warehouse_id', 'quantity', 'product_sku','po_code'], 'required'],
            [['product_sku','po_code'], 'string'],
            [['po_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'warehouse_id' => 'Kho hàng',
            'product_sku' => 'Mã sản phẩm',
            'po_code' => 'Mã nhập hàng',
            'quantity' => 'Số lượng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(ProductsModel::className(), ['sku' => 'product_sku']);
    }

    public function getTransaction()
    {
        return $this->hasMany(WarehouseTransaction::className(), ['warehouse_id' => 'warehouse_id']);
    }

    /**
     * @param $warehouse_id
     * @param $product_sku
     * @param $quantity
     * @param $transaction_type
     * @param null $note
     * @param null $order_code
     * @throws BadRequestHttpException
     */

    public static function doUpdateWarehouseTransaction($warehouse_id, $product_sku, $quantity, $transaction_type, $note = null, $order_code = null)
    {
        $warehouseStorage = WarehouseStorage::findOne(['warehouse_id' => $warehouse_id, 'product_sku' => $product_sku]);
        if (!$warehouseStorage) {
            throw new BadRequestHttpException('Không tìm thấy sản phẩm trong kho hàng!');
        }

        $warehouseTran = new WarehouseTransaction();
        $warehouseTran->warehouse_id = $warehouse_id;
        $warehouseTran->product_sku = $product_sku;
        $warehouseTran->quantity = $quantity;
        $warehouseTran->status = WarehouseStorage::STATUS_CONFIRM;
        $warehouseTran->transaction_type = $transaction_type;
        $warehouseTran->note = $note;
        $warehouseTran->order_code = $order_code;

        if (!$warehouseTran->save()) {
            throw new BadRequestHttpException(Helper::firstError($warehouseTran));
        }

        switch ($transaction_type) {
            case WarehouseTransaction::TRANSACTION_TYPE_IMPORT:
            case WarehouseTransaction::TRANSACTION_TYPE_REFUND:
                // Nhập kho hoặc hoàn đơn
                $warehouseStorage->quantity = $warehouseStorage->quantity + $quantity;
                break;
            case WarehouseTransaction::TRANSACTION_TYPE_EXPORT:
            case WarehouseTransaction::TRANSACTION_TYPE_BROKEN:
                // Xuất kho hoặc hỏng
                if ($warehouseStorage->quantity < $quantity) {
                    throw new BadRequestHttpException('số lượng kho sản phẩm không đủ!');
                }
                $warehouseStorage->quantity = $warehouseStorage->quantity - $quantity;
                break;
        }
        if (!$warehouseStorage->save()) {
            throw new BadRequestHttpException(Helper::firstError($warehouseStorage));
        }
    }
}
