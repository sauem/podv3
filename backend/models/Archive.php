<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "archive".
 *
 * @property int $id
 * @property int|null $name
 * @property string|null $address
 * @property int|null $phone
 * @property string|null $logo
 * @property string|null $domain
 * @property int $created_at
 * @property int $updated_at
 */
class Archive extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'created_at', 'updated_at'], 'integer'],
            [['logo', 'domain', 'name'], 'string'],
            [['name'], 'required'],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'address' => 'Địa chỉ',
            'phone' => 'điện thoại',
            'logo' => 'Logo',
            'domain' => 'Domain',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
