<?php

namespace backend\models;

use Yii;
use yii\mail\BaseMessage;

/**
 * This is the model class for table "form_info_sku".
 *
 * @property int $id
 * @property int|null $info_id
 * @property string|null $sku
 * @property int|null $qty
 * @property int $created_at
 * @property int $updated_at
 *
 * @property FormInfo $info
 */
class FormInfoSku extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_info_sku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['info_id', 'qty', 'updated_at', 'created_at'], 'integer'],
            [['info_id', 'qty', 'sku'], 'required'],
            [['sku'], 'string', 'max' => 255],
            [['info_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormInfo::className(), 'targetAttribute' => ['info_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'info_id' => 'Info ID',
            'sku' => 'Sku',
            'qty' => 'Qty',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Info]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(FormInfo::className(), ['id' => 'info_id']);
    }
    public function getProduct()
    {
        return $this->hasOne(ProductsModel::className(), ['sku' => 'sku']);
    }
}
