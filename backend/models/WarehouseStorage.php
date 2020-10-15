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
 * @property int|null $product_id
 * @property int|null $quantity
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
            [['warehouse_id', 'product_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['warehouse_id', 'quantity', 'product_id'], 'required'],
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
    public function getProduct(){
            return $this->hasOne(ProductsModel::className(),['id' => 'product_id']);
    }

    public static function doUpdateWarehouseTransaction($warehouse_id, $quantity, $product_id, $transaction_type, $note = null, $order_code = null)
    {
        $warehouseStorage = WarehouseStorage::findOne(['product_id' => $product_id]);
        if (!$warehouseStorage) {
            throw new BadRequestHttpException('Không tìm thấy sản phẩm trong kho hàng!');
        }

        $warehouseTran = new WarehouseTransaction();
        $warehouseTran->warehouse_id = $warehouse_id;
        $warehouseTran->product_id = $product_id;
        $warehouseTran->quantity = $quantity;
        $warehouseTran->status = WarehouseStorage::STATUS_CONFIRM;
        $warehouseTran->transaction_type = $transaction_type;
        $warehouseTran->note = $note;
        $warehouseTran->order_code = $order_code;

        if (!$warehouseTran->save()) {
            throw new BadRequestHttpException(Helper::firstError($warehouseTran));
        }

        if ($warehouseTran->transaction_type === WarehouseTransaction::TRANSACTION_TYPE_EXPORT) {
            if ($warehouseStorage->quantity < $quantity) {
                throw new BadRequestHttpException('Quantity not enough!');
            }
            $warehouseStorage->quantity = $warehouseStorage->quantity - $quantity;
        } else {
            $warehouseStorage->quantity = $warehouseStorage->quantity + $quantity;
        }

        if (!$warehouseStorage->save()) {
            throw new BadRequestHttpException(Helper::firstError($warehouseStorage));
        }
    }
}
