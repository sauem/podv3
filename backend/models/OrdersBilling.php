<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orders_billing".
 *
 * @property int $id
 * @property int|null $order_id
 * @property string|null $path
 * @property string|null $active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Orders $order
 */
class OrdersBilling extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const DRAFT  = "draf";
    const ACTIVE = "active";
    const STATUS = [
        self::DRAFT => 'Nháp',
        self::ACTIVE => 'Kích hoạt'
    ];
    public static function tableName()
    {
        return 'orders_billing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'created_at', 'updated_at'], 'integer'],
            [['path'], 'string'],
            [['path'], 'required'],
            [['active'], 'string', 'max' => 50],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdersModel::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'path' => 'Path',
            'active' => 'Active',
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
}
