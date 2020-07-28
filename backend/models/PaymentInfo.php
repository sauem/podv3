<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "payment_info".
 *
 * @property int $id
 * @property int|null $payment_id
 * @property string|null $bank_account
 * @property string|null $bank_name
 * @property string|null $bank_number
 * @property string|null $bank_address
 * @property string|null $bank_description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Payment $payment
 */
class PaymentInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_id'], 'integer'],
            [['bank_name', 'bank_number','bank_account','bank_address'], 'required'],
            [['bank_account', 'bank_name', 'bank_number', 'bank_address', 'bank_description'], 'string', 'max' => 255],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_id' => 'Payment ID',
            'bank_account' => 'Chủ tài khoản',
            'bank_name' => 'Ngân hàng',
            'bank_number' => 'Số tài khoản',
            'bank_address' => 'Chi nhánh',
            'bank_description' => 'Mô tả',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }
}
