<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property int|null $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $city
 * @property string|null $address
 * @property string|null $district
 * @property string|null $zipcode
 * @property string|null $country
 * @property int $created_at
 * @property int $updated_at
 */
class Customers extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone','country'], 'required'],
            [['phone'], 'string', 'max' => 15],
            [['email', 'district', 'country'], 'string', 'max' => 65],
            [['city'], 'string', 'max' => 100],
            [['address','name'], 'string', 'max' => 255],
            [['zipcode'], 'string', 'max' => 50],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'city' => 'City',
            'address' => 'Address',
            'district' => 'District',
            'zipcode' => 'Zipcode',
            'country' => 'Country',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
